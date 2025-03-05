<?php

namespace App\Http\Controllers;

use App\Models\CsvField;
use App\Models\CsvUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CsvFieldController extends Controller
{
    /** get all the addresses uploaded by the user
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $csvUploads = CsvUpload::with('csvFields')->where('uploaded_by', $userId)->get();
        $addresses = [];

        foreach ($csvUploads as $csvUpload) {
            foreach ($csvUpload->csvFields as $csvField) {
                $fieldData = json_decode($csvField->field_data, true);
                $address = $fieldData['address'] ?? null;

                if ($address) {
                    $addresses[] = [
                        'address' => $address,
                        'validation_status' => $csvField->validation_status,
                    ];
                }
            }
        }

        return response()->json([
            'addresses' => $addresses,
        ]);
    }

}
