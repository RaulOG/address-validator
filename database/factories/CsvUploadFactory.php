<?php

// database/factories/CsvUploadFactory.php
namespace Database\Factories;

use App\Models\CsvUpload;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CsvUploadFactory extends Factory
{
    protected $model = CsvUpload::class;

    public function definition()
    {
        return [
            'file_name' => $this->faker->word . '.csv',
            'file_path' => storage_path('app/' . $this->faker->word . '.csv'),
            'uploaded_by' => User::factory()->create()->id,
            'uploaded_at' => now(),
            'field_mapping' => json_encode(['address' => 'AddressField']),
        ];
    }

}

