<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Listing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessListingImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $listingId,
        public array $mediaFiles,
        public string $phoneNumber
    ) {
        // Set queue connection and queue name
        $this->onQueue('images');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $listing = Listing::find($this->listingId);

            if (! $listing) {
                Log::error('Listing not found for image processing', [
                    'listing_id' => $this->listingId,
                ]);

                return;
            }

            Log::info('Starting background image processing', [
                'listing_id' => $this->listingId,
                'phone_number' => $this->phoneNumber,
                'media_count' => count($this->mediaFiles),
            ]);

            $processedImages = $listing->processImagesFromS3(
                $this->mediaFiles,
                $this->phoneNumber
            );

            Log::info('Background image processing completed', [
                'listing_id' => $this->listingId,
                'phone_number' => $this->phoneNumber,
                'processed_count' => count($processedImages),
                'media_files' => $this->mediaFiles,
            ]);

        } catch (\Exception $e) {
            Log::error('Background image processing failed', [
                'listing_id' => $this->listingId,
                'phone_number' => $this->phoneNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Image processing job failed permanently', [
            'listing_id' => $this->listingId,
            'phone_number' => $this->phoneNumber,
            'media_files' => $this->mediaFiles,
            'error' => $exception->getMessage(),
        ]);
    }
}
