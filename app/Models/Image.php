<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $storage_path
 */
class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_name',
        'storage_path',
        'url',
        'status',
        'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];
}
