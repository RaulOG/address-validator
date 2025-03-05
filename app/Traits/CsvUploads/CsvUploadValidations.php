<?php

namespace App\Traits\CsvUploads;

use App\Models\CsvUpload;

trait CsvUploadValidations
{
    /**validations for upload; file is required and should be of type csv
     * @return string[]
     */
    public static function listUploadValidations(): array
    {
        return [
            'file' => 'required|file|mimes:' . implode(',', CsvUpload::$allowedUploadMimeTypes),
            'mappings' => 'required|string',
        ];
    }
}
