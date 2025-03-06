<?php

namespace Tests\Feature;

use App\Jobs\ProcessCsvUploadJob;
use App\Models\CsvUpload;
use App\Models\User;
use Illuminate\Bus\Dispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CsvUploadControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->user = $user;
        $this->actingAs($user);
    }

    #[Test] public function invalidates_uploads() {
        // Assert that invalid uploads such as missing file are not accepted
    }

    #[Test] public function dispatches_csv_upload_job()
    {
        $this->mock(Dispatcher::class, function (MockInterface $mock) {
            $mock->shouldReceive('dispatch')->once()->with(ProcessCsvUploadJob::class);
        });

        $file = UploadedFile::fake()->create('addresses.csv', 100, 'text/csv');
        $response = $this->post(route('csv.upload'), [
            'file' => $file,
            'mappings' => json_encode(['address' => 'AddressField']),
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('csv_uploads', [
            'file_name' => 'addresses.csv',
            'uploaded_by' => $this->user->id,
        ]);
    }
}
