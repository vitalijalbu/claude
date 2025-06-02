<?php

namespace App\Jobs;

use App\Models\Listing;
use App\Models\Profile;
use App\Services\MediaService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessListingImages implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $maxExceptions = 3;

    public int $timeout = 300; // 5 minutes

    public function __construct(
        public Listing $listing,
        public Profile $profile,
        public array $imageFilenames
    ) {}

    public function handle(MediaService $mediaService): void
    {
        try {
            Log::info('Starting image processing', [
                'listing_id' => $this->listing->id,
                'profile_id' => $this->profile->id,
                'image_count' => count($this->imageFilenames),
            ]);

            $processedImages = $mediaService->attachImagesFromLocalRaw(
                $this->profile,
                $this->listing,
                $this->imageFilenames
            );

            // Update listing with processed images info
            $this->listing->update([
                'media' => array_column($processedImages, 'original'),
            ]);

            Log::info('Image processing completed', [
                'listing_id' => $this->listing->id,
                'processed_count' => count($processedImages),
            ]);

        } catch (\Throwable $e) {
            Log::error('Image processing failed', [
                'listing_id' => $this->listing->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Image processing job failed permanently', [
            'listing_id' => $this->listing->id,
            'profile_id' => $this->profile->id,
            'error' => $exception->getMessage(),
        ]);

        // Optionally notify admins or mark listing as having processing issues
        $this->listing->update(['media_processing_failed' => true]);
    }
}
