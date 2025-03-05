<?php

// tests/Feature/CsvUploadControllerTest.php
namespace Tests\Feature;

use App\Models\CsvUpload;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CsvUploadControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // Test the CSV upload route
    public function test_it_can_upload_a_csv_file()
    {
        $user = User::factory()->create(); // Create a user for testing

        $this->actingAs($user);
        $file = UploadedFile::fake()->create('addresses.csv', 100, 'text/csv');
        $response = $this->post(route('csv.upload'), [
            'file' => $file,
            'mappings' => json_encode(['address' => 'AddressField']),
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('csv_uploads', [
            'file_name' => 'addresses.csv',
            'uploaded_by' => $user->id,
        ]);
    }





}

