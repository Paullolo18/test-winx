<?php

namespace App\Http\Controllers;

use App\Http\Requests\CSVUploadRequest;
use Illuminate\Http\Request;
use App\Jobs\ProcessCsvJob;

class CSVUploadController extends Controller
{
    public function upload(Request $request)
    {   
        $empresa = auth()->user()->empresa;

        if (!$empresa) {
            return response()->json(['message' => 'Empresa não encontrada'], 403);
        }

        $file = $request->file('file');
        $filePath = $file->store('csv_uploads');

        ProcessCsvJob::dispatch($filePath, $empresa->id);

        return response()->json(['message' => 'Upload recebido! O processamento será feito em background.'], 200);
    }
}
