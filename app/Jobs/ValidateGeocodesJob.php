<?php

namespace App\Jobs;

use App\Exceptions\GeocodesBatchJobIsPendingException;
use App\Models\CsvField;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ValidateGeocodesJob implements ShouldQueue
{
    use Queueable;

    private int $csvUploadId;
    private string $batchJobId;
    public int $tries = 5;

    public function __construct(int $csvUploadId, string $batchJobId)
    {
        $this->csvUploadId = $csvUploadId;
        $this->batchJobId = $batchJobId;
    }

    public function handle(Client $client): void
    {
        try {
            $geocodes = $this->getGeocodesValidation($client);
            foreach ($geocodes as $geocode) {
                if ($this->isAddressValid($geocode)) {
                    CsvField::where('csv_upload_id', $this->csvUploadId)
                        ->whereJsonContains('field_data->address', $geocode->query->text)
                        ->update(['validation_status' => CsvField::VALID]);
                } else {
                    CsvField::where('csv_upload_id', $this->csvUploadId)
                        ->whereJsonContains('field_data->address', $geocode->query->text)
                        ->update(['validation_status' => CsvField::INVALID]);
                }
            }
        } catch (GeocodesBatchJobIsPendingException $e) {
            $this->release(30);
        }
    }

    /**
     * @param Client $client
     * @return array
     * @throws GeocodesBatchJobIsPendingException
     * @throws GuzzleException
     */
    private function getGeocodesValidation(Client $client): array
    {
        $response = $client->request('GET', 'https://api.geoapify.com/v1/batch/geocode/search', [
            'query' => [
                'id' => $this->batchJobId,
                'apiKey' => env('GEOAPIFY_API_KEY'),
                'format' => 'json',
            ],
        ]);

        $body = json_decode($response->getBody()->getContents());

        if (gettype($body) === 'object' && $body->status === 'pending') {
            throw new GeocodesBatchJobIsPendingException();
        }

        return $body;
    }

    /**
     * @param object $geocode
     * @return bool
     */
    private function isAddressValid(object $geocode): bool
    {
        return isset($geocode->query->parsed);
    }
}
