<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CsvUpload extends Model
{
   use SoftDeletes, HasFactory;

    protected $fillable = [
         'file_name',
         'file_path',
         'uploaded_by',
         'field_mapping',
         'uploaded_at',
    ];

    protected $casts = [
        'field_mapping' => 'array',
    ];
}
