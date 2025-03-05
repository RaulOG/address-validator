<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CsvUploadRequest extends FormRequest
{
    /** Get the validation rules that apply to the request.
     * @return array
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:csv',
            'mappings' => 'required|string',
        ];
    }
}
