<?php

namespace App\Enums;

enum ImageAction: string
{
    case DIMENSIONS = 'changeDimensions';
    case WEBP = 'changeToWebp';
    case WATERMARK = 'addWatermark';
}
