<?php

namespace App\Jobs;

use App\Jobs\BaseImageProcessingJob;
use App\Models\Image;

class AddWatermarkJob extends BaseImageProcessingJob
{
    public function __construct(
        public Image $image,
        public string $watermark,
    ) {
        parent::__construct($image);
    }

    public function handleInner()
    {
        // TODO: Implement handleInner() method.
    }
}
