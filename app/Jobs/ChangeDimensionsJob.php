<?php

namespace App\Jobs;

use App\Models\Image;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ChangeDimensionsJob extends BaseImageProcessingJob
{
    public function __construct(
        public Image $image,
        public int $width,
        public int $height
    ) {
        parent::__construct($image);
    }

    public function handleInner(): void
    {
        Log::info('Changing dimensions');

        $fullPath = storage_path('app/public/' . $this->image->storage_path);

        $manager = new ImageManager(new Driver());

        $img = $manager->read($fullPath);

        $img->resize($this->width, $this->height);

        $img->save($fullPath);
    }
}
