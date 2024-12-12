<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportStatus;
use App\Traits\HttpResponses;

class ImportStatusController extends Controller
{
    use HttpResponses;

    public function getImportStatus()
    {
        $importStatus = ImportStatus::orderBy('updated_at', 'desc')->get();
        return response()->json($importStatus);
    }

    public function getImportStatusWithPagination()
    {
        $importStatus = ImportStatus::orderBy('updated_at', 'desc')->get();
        return $this->responseSuccess(['importStatus' => $importStatus]);
    }

    public function index()
    {
        return view('admin.import-status');
    }
}
