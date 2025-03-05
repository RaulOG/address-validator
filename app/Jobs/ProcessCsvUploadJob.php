<?php

namespace App\Jobs;

use App\Models\CsvField;
use App\Models\CsvUpload;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ProcessCsvUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected CsvUpload $csvUpload;
    protected array $mappings;

    public function __construct(CsvUpload $csvUpload, array $mappings)
    {
        $this->csvUpload = $csvUpload;
        $this->mappings = $mappings;
    }

    /** handles the CSV upload processing
     * @return void
     * @throws GuzzleException
     */
    public function handle(): void
    {
        $filePath = $this->csvUpload->file_path;
        $rows = $this->readCsv($filePath);
        $existingAddresses = $this->getExistingAddresses();
        $recordsToInsert = $this->processRows($rows, $existingAddresses);

        // Batch insert if there are records to insert
        if (!empty($recordsToInsert)) {
            CsvField::insert($recordsToInsert);
        }
        $this->validateAddresses($existingAddresses);
    }

    /** reads the CSV file
     * @param string $filePath
     * @return array
     */
    private function readCsv(string $filePath): array
    {
        return array_map('str_getcsv', file(storage_path("app/private/$filePath")));
    }

    /** check for existing addresses in the CSV upload
     * @return array
     */
    private function getExistingAddresses(): array
    {
        return CsvField::where('csv_upload_id', $this->csvUpload->id)
            ->pluck('field_data->address')->toArray();
    }

    /** process the rows of the CSV file
     * @param array $rows
     * @param array $existingAddresses
     * @return array
     */
    private function processRows(array $rows, array &$existingAddresses): array
    {
        $recordsToInsert = [];

        // Get the header from the first row
        $header = array_shift($rows);

        foreach ($rows as $row) {
            $dataToSave = [];
            $addressField = null;

            // Map fields based on user input
            $this->mapFields($header, $row, $dataToSave, $addressField);

            if ($addressField) {
                $recordsToInsert = $this->prepareRecordsToInsert($dataToSave, $existingAddresses, $recordsToInsert, $addressField);
            }
        }

        return $recordsToInsert;
    }

    /** map fields based on user input ; for now we are assuming that the address field is mandatory
     * @param array $header
     * @param array $row
     * @param array $dataToSave
     * @param string|null $addressField
     * @return void
     */
    private function mapFields(array $header, array $row, array &$dataToSave, ?string &$addressField): void
    {
        foreach ($this->mappings as $key => $mappedField) {
            $index = array_search($mappedField, $header);
            if ($index !== false) {
                $dataToSave[$key] = $row[$index] ?? null;
                if (strtolower($key) === 'address') {
                    $addressField = $row[$index] ?? null;
                }
            }
        }
    }

    /** this will take to account the valid rows and prepare them for batch insertion
     * @param array $dataToSave
     * @param array $existingAddresses
     * @param array $recordsToInsert
     * @param string $addressField
     * @return array
     */
    private function prepareRecordsToInsert(array $dataToSave, array &$existingAddresses, array $recordsToInsert, string $addressField): array
    {
        if (!in_array($addressField, $existingAddresses)) {
            $recordsToInsert[] = [
                'csv_upload_id' => $this->csvUpload->id,
                'field_data' => json_encode($dataToSave),
                'validation_status' => CsvField::PENDING,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $existingAddresses[] = $addressField;
        } else {
            Log::info("Duplicate address skipped: " . $addressField);
        }

        return $recordsToInsert;
    }

    /** this will validate the addresses as a batch using Geoapify API
     * @param array $addresses
     * @return void
     * @throws GuzzleException
     */
    protected function validateAddresses(array $addresses): void
    {
        $client = new Client();

        $response = $client->request('POST', 'https://api.geoapify.com/v1/batch/geocode/search', [
            'json' => $addresses,
            'query' => [
                'apiKey' => env('GEOAPIFY_API_KEY'),
            ]
        ]);
        $body = json_decode($response->getBody(), true);

        ValidateGeocodesJob::dispatch($this->csvUpload->id, $body['id']);
    }
}
