<?php

namespace App\Traits\CsvUploads;

use App\Models\CsvField;

trait CsvUploadRelations
{
    public function csvFields()
    {
        return $this->hasMany(CsvField::class);
    }
}
