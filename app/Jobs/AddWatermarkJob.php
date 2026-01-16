<?php

namespace App\Jobs;

use App\Models\Image;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Typography\FontFactory;

class AddWatermarkJob extends BaseImageProcessingJob
{
    public function __construct(
        public Image $image,
        public string $watermark,
    ) {
        parent::__construct($image);
    }

    public function handleInner(): void
    {
        $fullPath = storage_path('app/public/' . $this->image->storage_path);

        $manager = new ImageManager(new Driver());
        $img = $manager->read($fullPath);

        $img->text($this->watermark, $img->width() / 2, $img->height() / 2, function (FontFactory $font) {
            $font->size(50);
            $font->color('ffffff');
            $font->align('center');
            $font->valign('middle');
        });

        $img->save($fullPath);
    }
}
