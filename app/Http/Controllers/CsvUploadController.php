<?php

namespace App\Http\Controllers;

use App\Http\Requests\CsvUploadRequest;
use App\Jobs\ProcessCsvUploadJob;
use App\Models\CsvUpload;
use Illuminate\Bus\Dispatcher;
use Illuminate\Http\JsonResponse;

class CsvUploadController extends Controller
{
    /** uploads the CSV file and its mappings
     * @param CsvUploadRequest $request
     * @param Dispatcher $dispatcher
     * @return JsonResponse
     */
    public function upload(CsvUploadRequest $request, Dispatcher $dispatcher): JsonResponse
    {
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('csv_uploads');

            $csvUpload = CsvUpload::create([
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_path' => $filePath,
                'uploaded_by' => auth()->id(),
                'field_mapping' => $request->input('mappings'),
                'uploaded_at' => now(),
            ]);

            $mappings = [];
            foreach (explode(',', $request->input('mappings')) as $mapping) {
                $mappings[$mapping] = $mapping;
            }

            $dispatcher->dispatch(new ProcessCsvUploadJob($csvUpload, $mappings));

            return response()->json(['message' => 'File uploaded successfully.'], 200);
        }

        return response()->json(['message' => 'No file uploaded.'], 400);
    }
}
