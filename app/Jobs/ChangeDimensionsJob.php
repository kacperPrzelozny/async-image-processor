<?php

namespace App\Jobs;

use App\Jobs\BaseImageProcessingJob;
use App\Models\Image;

class ChangeDimensionsJob extends BaseImageProcessingJob
{
    public function __construct(
        public Image $image,
        public int $width,
        public int $height
    ) {
        parent::__construct($image);
    }

    public function handleInner()
    {
        // TODO: Implement handleInner() method.
    }
}
