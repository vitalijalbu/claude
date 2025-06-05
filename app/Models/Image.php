<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\Cacheable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Laravel\Facades\Image as ImageRes;

class Image extends Model
{
    //use Cacheable;

    protected $fillable = ['path', 'alt', 'extension_original', 'file_size', 'width', 'height', 'order_column'];

    protected $casts = [
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'order_column' => 'integer',
    ];

    /**
     * Configurazioni delle dimensioni per e-commerce
     */
    protected static array $sizes = [
        'xs' => ['x' => 64, 'y' => 64],
        'sm' => ['x' => 150, 'y' => 150],
        'md' => ['x' => 300, 'y' => 300],
        'lg' => ['x' => 600, 'y' => 600],
        'original' => null,
    ];

    /**
     * Estensioni supportate
     */
    protected static array $extensions = ['webp', 'jpg'];

    /**
     * Relationships
     */
    public function listings(): MorphToMany
    {
        return $this->morphedByMany(Listing::class, 'imageable')
            ->withTimestamps()
            ->orderBy('images.order_column');
    }

    /**
     * Salva immagine da upload diretto
     */
    public static function saveImage(Request $request, string $phoneNumber): Request
    {
        $imageName = Str::slug($request->alt ?: 'image').'-'.time();
        $file = $request->file('image');
        $originalExtension = strtolower($file->extension());

        // Ottieni dimensioni originali
        $originalImage = ImageRes::read($file);

        $request->merge([
            'extension_original' => $originalExtension,
            'file_size' => $file->getSize(),
            'width' => $originalImage->width(),
            'height' => $originalImage->height(),
        ]);

        // Processa tutte le dimensioni
        self::processToFlatStructure($file, $imageName, $phoneNumber, $originalExtension);

        $request->merge(['path' => $phoneNumber.'/'.$imageName]);

        return $request;
    }

    /**
     * Processa immagini da S3 raw folder - VERSIONE SEMPLIFICATA
     */
    public static function processImagesFromS3(array $s3Filenames, string $phoneNumber): array
    {
        $processedImages = [];
        $sourceDir = "raw/{$phoneNumber}";

        foreach ($s3Filenames as $index => $filename) {
            try {
                $sourceFile = "{$sourceDir}/{$filename}";

                // Controlla se il file esiste
                if (! Storage::disk('s3')->exists($sourceFile)) {
                    Log::warning("File S3 non trovato: {$sourceFile}");

                    continue;
                }

                // Scarica temporaneamente il file
                $fileContent = Storage::disk('s3')->get($sourceFile);
                $tempPath = storage_path('app/temp/'.$filename);

                File::ensureDirectoryExists(dirname($tempPath));
                File::put($tempPath, $fileContent);

                // Controlla se è un'immagine valida
                if (! self::isValidImage($tempPath)) {
                    Log::warning("File non valido: {$sourceFile}");
                    File::delete($tempPath);

                    continue;
                }

                // Ottieni info immagine
                $originalImage = ImageRes::read($tempPath);
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $baseName = pathinfo($filename, PATHINFO_FILENAME);

                // Processa in struttura flat
                self::processToFlatStructure($tempPath, $baseName, $phoneNumber, $extension);

                // Pulisci file temporaneo
                File::delete($tempPath);

                // Crea record nel database - CON VALIDAZIONE
                $imageData = [
                    'path' => $phoneNumber.'/'.$baseName,
                    'alt' => $baseName,
                    'extension_original' => $extension,
                    'file_size' => strlen($fileContent),
                    'width' => $originalImage->width(),
                    'height' => $originalImage->height(),
                    'order_column' => $index,
                ];

                // VALIDAZIONE PRIMA DEL SALVATAGGIO
                if (self::validateImageData($imageData)) {
                    $image = self::create($imageData);
                    $processedImages[] = $image;

                    Log::info('Immagine processata con successo', [
                        'filename' => $filename,
                        'image_id' => $image->id,
                    ]);
                } else {
                    Log::error('Dati immagine non validi', ['data' => $imageData]);
                }

            } catch (\Exception $e) {
                Log::error('Errore nel processare immagine da S3', [
                    'filename' => $filename,
                    'phone_number' => $phoneNumber,
                    'error' => $e->getMessage(),
                ]);

                continue;
            }
        }

        return $processedImages;
    }

    /**
     * VALIDAZIONE DATI IMMAGINE - AGGIUNTO
     */
    private static function validateImageData(array $data): bool
    {
        return ! empty($data['path']) &&
               ! empty($data['extension_original']) &&
               isset($data['file_size']) && $data['file_size'] > 0 &&
               isset($data['width']) && $data['width'] > 0 &&
               isset($data['height']) && $data['height'] > 0 &&
               isset($data['order_column']) && $data['order_column'] >= 0;
    }

    /**
     * CONTROLLO SE FILE È IMMAGINE VALIDA - AGGIUNTO
     */
    private static function isValidImage(string $filePath): bool
    {
        try {
            $imageInfo = getimagesize($filePath);

            return $imageInfo !== false && in_array($imageInfo['mime'], [
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/gif',
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Processa immagine in struttura flat
     */
    private static function processToFlatStructure($source, string $baseName, string $phoneNumber, string $originalExtension): void
    {
        $targetDir = "media/{$phoneNumber}";
        $localTargetDir = storage_path("app/public/{$targetDir}");

        // Assicurati che la directory esista
        File::ensureDirectoryExists($localTargetDir);

        foreach (self::$sizes as $sizeKey => $sizeConfig) {
            if ($sizeKey === 'original') {
                // File originale
                $filename = "{$baseName}.{$originalExtension}";
                $localPath = "{$localTargetDir}/{$filename}";
                $s3Path = "{$targetDir}/{$filename}";

                if (is_string($source)) {
                    File::copy($source, $localPath);
                } else {
                    $source->move($localTargetDir, $filename);
                    $localPath = "{$localTargetDir}/{$filename}";
                }

                // Upload su S3 con retry
                self::uploadToS3($s3Path, $localPath);
            } else {
                // Versioni ridimensionate
                foreach (self::$extensions as $ext) {
                    try {
                        $filename = "{$baseName}-{$sizeKey}.{$ext}";
                        $localPath = "{$localTargetDir}/{$filename}";
                        $s3Path = "{$targetDir}/{$filename}";

                        $processedImage = self::makeImage($source, $ext, $sizeConfig);
                        $processedImage->save($localPath);

                        // Upload su S3 con retry
                        self::uploadToS3($s3Path, $localPath);

                    } catch (\Exception $e) {
                        Log::error('Errore nel processare dimensione immagine', [
                            'base_name' => $baseName,
                            'size' => $sizeKey,
                            'extension' => $ext,
                            'error' => $e->getMessage(),
                        ]);

                        continue;
                    }
                }
            }
        }
    }

    /**
     * UPLOAD SU S3 CON RETRY - AGGIUNTO
     */
    private static function uploadToS3(string $s3Path, string $localPath, int $maxRetries = 3): void
    {
        $attempt = 0;

        while ($attempt < $maxRetries) {
            try {
                Storage::disk('s3')->put($s3Path, File::get($localPath));

                return;
            } catch (\Exception $e) {
                $attempt++;
                if ($attempt >= $maxRetries) {
                    Log::error('Fallito upload S3 dopo tentativi', [
                        's3_path' => $s3Path,
                        'error' => $e->getMessage(),
                    ]);
                    throw $e;
                }

                // Aspetta prima del retry
                sleep($attempt);
            }
        }
    }

    /**
     * Elimina tutti i file immagine da S3 e storage locale
     */
    public static function deleteImages(self $image): void
    {
        $filesToDelete = [];
        $pathParts = explode('/', $image->path);
        $phoneNumber = $pathParts[0];
        $baseName = $pathParts[1];

        // File originale
        $filesToDelete[] = "media/{$phoneNumber}/{$baseName}.{$image->extension_original}";

        // Tutte le versioni ridimensionate
        foreach (array_keys(self::$sizes) as $sizeKey) {
            if ($sizeKey === 'original') {
                continue;
            }

            foreach (self::$extensions as $ext) {
                $filesToDelete[] = "media/{$phoneNumber}/{$baseName}-{$sizeKey}.{$ext}";
            }
        }

        // Elimina da S3
        try {
            Storage::disk('s3')->delete($filesToDelete);
        } catch (\Exception $e) {
            Log::error('Errore nell\'eliminare file da S3', [
                'files' => $filesToDelete,
                'error' => $e->getMessage(),
            ]);
        }

        // Elimina file locali
        foreach ($filesToDelete as $file) {
            $localPath = storage_path('app/public/'.$file);
            if (File::exists($localPath)) {
                File::delete($localPath);
            }
        }
    }

    /**
     * Crea immagine ridimensionata usando Intervention Image
     */
    public static function makeImage($source, string $extension, array $size): ImageInterface
    {
        $image = ImageRes::read($source);

        // Imposta qualità in base al formato
        $quality = match ($extension) {
            'avif' => 85,
            'webp' => 90,
            'jpg', 'jpeg' => 92,
            'png' => 100,
            default => 90
        };

        $image->scaleDown($size['x'], $size['y']);
        $image->encodeByExtension($extension, quality: $quality);

        return $image;
    }

    /**
     * Ottieni URL immagine per dimensione specifica
     */
    public function path(string $format = 'webp', string $size = 'md'): string
    {
        $pathParts = explode('/', $this->path);
        $phoneNumber = $pathParts[0];
        $baseName = $pathParts[1];

        if ($size === 'original') {
            $filename = "{$baseName}.{$this->extension_original}";
        } else {
            $filename = "{$baseName}-{$size}.{$format}";
        }

        return env('CDN_URL')."/media/{$phoneNumber}/{$filename}";
    }

    /**
     * Ottieni percorso file locale per dimensione specifica
     */
    public function localPath(string $format = 'webp', string $size = 'md'): string
    {
        $pathParts = explode('/', $this->path);
        $phoneNumber = $pathParts[0];
        $baseName = $pathParts[1];

        if ($size === 'original') {
            $filename = "{$baseName}.{$this->extension_original}";
        } else {
            $filename = "{$baseName}-{$size}.{$format}";
        }

        return storage_path("app/public/media/{$phoneNumber}/{$filename}");
    }

    /**
     * Ottieni tutti gli URL delle dimensioni disponibili
     */
    public function getAllSizeUrls(): array
    {
        $pathParts = explode('/', $this->path);
        $phoneNumber = $pathParts[0];
        $baseName = $pathParts[1];

        return [
            'original' => env('CDN_URL')."/media/{$phoneNumber}/{$baseName}.{$this->extension_original}",
            'xs' => env('CDN_URL')."/media/{$phoneNumber}/{$baseName}-xs.webp",
            'sm' => env('CDN_URL')."/media/{$phoneNumber}/{$baseName}-sm.webp",
            'md' => env('CDN_URL')."/media/{$phoneNumber}/{$baseName}-md.webp",
            'lg' => env('CDN_URL')."/media/{$phoneNumber}/{$baseName}-lg.webp",
        ];
    }

    /**
     * Ottieni URL responsive per diversi formati
     */
    public function getResponsiveUrls(string $size = 'md'): array
    {
        $pathParts = explode('/', $this->path);
        $phoneNumber = $pathParts[0];
        $baseName = $pathParts[1];

        if ($size === 'original') {
            return [
                'original' => env('CDN_URL')."/media/{$phoneNumber}/{$baseName}.{$this->extension_original}",
            ];
        }

        return [
            'webp' => env('CDN_URL')."/media/{$phoneNumber}/{$baseName}-{$size}.webp",
            'jpg' => env('CDN_URL')."/media/{$phoneNumber}/{$baseName}-{$size}.jpg",
        ];
    }

    /**
     * Controlla se il file immagine esiste nello storage
     */
    public function exists(string $size = 'original', string $format = 'webp'): bool
    {
        $pathParts = explode('/', $this->path);
        $phoneNumber = $pathParts[0];
        $baseName = $pathParts[1];

        if ($size === 'original') {
            $filename = "{$baseName}.{$this->extension_original}";
        } else {
            $filename = "{$baseName}-{$size}.{$format}";
        }

        return Storage::disk('s3')->exists("media/{$phoneNumber}/{$filename}");
    }

    /**
     * Scopes
     */
    public function scopePrimary($query)
    {
        return $query->orderBy('order_column')->first();
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_column');
    }
}
