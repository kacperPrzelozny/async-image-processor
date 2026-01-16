<?php

namespace App\Jobs;

use App\Models\Image;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class ConvertToWebpJob extends BaseImageProcessingJob
{
    public function __construct(
        public Image $image,
        public int $quality,
    ) {
        parent::__construct($image);
    }

    public function handleInner(): void
    {
        $originalPath = storage_path('app/public/' . $this->image->storage_path);

        $manager = new ImageManager(new Driver());
        $img = $manager->read($originalPath);

        $newFileName = pathinfo($this->image->storage_path, PATHINFO_FILENAME) . '.webp';
        $newRelativePath = 'images/' . $newFileName;
        $destinationPath = storage_path('app/public/' . $newRelativePath);

        $encoded = $img->encode(new WebpEncoder(quality: $this->quality));
        $encoded->save($destinationPath);

        if (file_exists($originalPath) && $originalPath !== $destinationPath) {
            unlink($originalPath);
        }

        $this->image->update([
            'storage_path' => $newRelativePath
        ]);
    }
}
