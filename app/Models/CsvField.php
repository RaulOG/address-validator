<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvField extends Model
{
    use hasFactory;

    const string PENDING = 'pending';
    const string INVALID = 'invalid';
    const string VALID = 'valid';

    protected $fillable = [
        'csv_upload_id',
        'field_data',
        'validation_status',
    ];

    protected $casts = [
        'field_data' => 'array',
    ];

    public function csvUpload()
    {
        return $this->belongsTo(CsvUpload::class);
    }
}
