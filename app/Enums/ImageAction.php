<?php

namespace App\Enums;

enum ImageAction: string
{
    case DIMENSIONS = 'changeDimensions';
    case WEBP = 'convertToWebp';
    case WATERMARK = 'addWatermark';
}
