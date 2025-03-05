<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCsvUploadJob;
use App\Models\CsvUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CsvUploadController extends Controller
{
    /** uploads the CSV file and its mappings
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request)
    {
        $validated = $request->validate(CsvUpload::listUploadValidations());
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('csv_uploads');

            CsvUpload::create([
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_path' => $filePath,
                'uploaded_by' => auth()->id(),
                'field_mapping' => json_encode($request->input('mappings')),
                'uploaded_at' => now(),
            ]);

            return response()->json(['message' => 'File uploaded successfully.'], 200);
        }

        return response()->json(['message' => 'No file uploaded.'], 400);
    }


}
