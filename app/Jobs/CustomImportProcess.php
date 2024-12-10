<?php

namespace App\Jobs;

use App\Enums\ImportStatus as Status;
use App\Imports\CustomUsersImport;
use App\Imports\UsersImport;
use App\Models\ImportStatus;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CustomImportProcess implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $importId;
    public $timeout = 300;
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath, $importId)
    {
        $this->filePath = $filePath;
        $this->importId = $importId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $import = new CustomUsersImport();

        try {
            DB::beginTransaction();
            Excel::import($import, $this->filePath);

            $errors = $import->getErrors();

            if (empty($errors)) {
                ImportStatus::find($this->importId)->update([
                    'status' => Status::Done,
                    'message' => 'Imported successfully'
                ]);
                DB::commit();
            } else {
                DB::rollBack();
                ImportStatus::find($this->importId)->update([
                    'status' => Status::Failed,
                    'message' => nl2br(implode(PHP_EOL, $errors))
                ]);

                Log::warning('User import failed', [
                    'file' => $this->filePath,
                    'errors' => $errors,
                ]);
            }
            Storage::delete($this->filePath);
        } catch (\Exception $e) {
            ImportStatus::find($this->importId)->update([
                'status' => Status::Failed,
                'message' => $e->getMessage(),
            ]);

            Log::error('User import failed', [
                'file' => $this->filePath,
                'error' => $e->getMessage(),
            ]);

        }
    }
}
