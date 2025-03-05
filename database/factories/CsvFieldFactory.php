<?php

namespace Database\Factories;

use App\Models\CsvField;
use App\Models\CsvUpload;
use Illuminate\Database\Eloquent\Factories\Factory;

class CsvFieldFactory extends Factory
{
    // Define the associated model for the factory
    protected $model = CsvField::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'csv_upload_id' => CsvUpload::factory(),
            'field_data' => json_encode([
                'address' => $this->faker->address,
                'name' => $this->faker->name,
            ]),
            'validation_status' => $this->faker->randomElement(['Valid', 'Invalid']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
