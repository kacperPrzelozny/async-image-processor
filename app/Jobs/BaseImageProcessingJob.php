<?php

namespace App\Jobs;

use App\Enums\ImageStatus;
use App\Models\Image;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

abstract class BaseImageProcessingJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Image $image
    ) {}

    abstract public function handleInner();

    public function handle(): void
    {
        $this->image->update([
            'status' => ImageStatus::PROCESSING
        ]);

        $this->handleInner();

        $url = asset('storage/' . $this->image->storage_path);
        $this->image->update([
            'status' => ImageStatus::COMPLETED,
            'url' => $url,
            'processed_at' => now()
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        $this->image->update([
            'status' => ImageStatus::FAILED
        ]);
    }
}
