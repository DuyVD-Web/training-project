<?php

namespace App\Jobs;

use App\Enums\ImportStatus as Status;
use App\Imports\UsersImport;
use App\Models\ImportStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProcessImportUsers implements ShouldQueue
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
        try {
            $import = new UsersImport();
            Excel::import($import, $this->filePath);

            ImportStatus::find($this->importId)->update([
                'status' => Status::Done,
                'message' => 'Imported successfully'
            ]);
            Storage::delete($this->filePath);
        } catch (\Exception $e) {
            $errors = [];

            // If it's a validation exception, collect specific validation errors
            if ($e instanceof \Maatwebsite\Excel\Validators\ValidationException) {
                foreach ($e->failures() as $failure) {
                    $errors[] = sprintf(
                        "Row %d, Column %s: %s",
                        $failure->row(),
                        $failure->attribute(),
                        $failure->errors()[0]
                    );
                }
            }

            if (empty($errors)) {
                $errors[] = $e->getMessage();
            }

            // Log all errors
            Log::error('User import failed', [
                'file' => $this->filePath,
                'errors' => $errors,
            ]);

            // Format errors into a single string, limiting total length
            $errorMessage = nl2br(implode(PHP_EOL, $errors));

            ImportStatus::find($this->importId)->update([
                'status' => Status::Failed,
                'message' => $errorMessage,
            ]);
        }
    }
}
