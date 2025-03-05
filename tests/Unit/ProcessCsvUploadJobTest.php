<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\CsvUpload;
use App\Jobs\ProcessCsvUploadJob;
use Tests\TestCase;

class ProcessCsvUploadJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_with_valid_data_inserts_records()
    {
        // Create a CsvUpload record
        $csvUpload = CsvUpload::factory()->create([
            'file_path' => storage_path('test.csv'), // Ensure the file exists
            'field_mapping' => json_encode(['address' => 'AddressField', 'name' => 'NameField']),
        ]);

        // Fake a valid CSV file
        $filePath = storage_path('test.csv');
        file_put_contents($filePath, "AddressField,NameField\n123 Main St,John Doe\n456 Maple St,Jane Doe");

        // Mock the validateAddress method
        $job = $this->getMockBuilder(ProcessCsvUploadJob::class)
            ->setConstructorArgs([$csvUpload, json_decode($csvUpload->field_mapping, true)])
            ->onlyMethods(['validateAddress']) // Only mock the validateAddress method
            ->getMock();

        $job->expects($this->any())
            ->method('validateAddress')
            ->willReturn('Valid'); // Return 'Valid' for all addresses

        // Run the job's handle method
        $job->handle();

        // Assert that records were inserted
        $this->assertDatabaseHas('csv_fields', [
            'csv_upload_id' => $csvUpload->id,
            'validation_status' => 'Valid',
        ]);
    }
}
