<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportStatus;

class ImportStatusController extends Controller
{
    public function getImportStatus()
    {
        $importStatus = ImportStatus::orderBy('updated_at', 'desc')->get();
        return response()->json($importStatus);
    }

    public function index()
    {
        return view('admin.import-status');
    }
}
