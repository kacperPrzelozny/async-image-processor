<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition(): array
    {
        return [
            'original_name' => $this->faker->name(),
            'storage_path' => $this->faker->filePath(),
            'url' => $this->faker->imageUrl(),
            'status' => $this->faker->randomElement(['pending', 'completed']),
            'processed_at' => $this->faker->dateTime(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }
}
