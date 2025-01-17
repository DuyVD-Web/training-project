<?php

namespace App\Jobs;

use App\Enums\ImportStatus as Status;
use App\Imports\UsersImport;
use App\Models\ImportStatus;
use Exception;
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
    use Queueable, InteractsWithQueue, SerializesModels;

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
        $import = new UsersImport();

        try {
            DB::beginTransaction();
            Excel::import($import, $this->filePath);

            // Get any errors that occurred during import
            $errors = $import->getErrors();

            if (empty($errors)) {
                // No errors, update status to done
                ImportStatus::find($this->importId)->update([
                    'status' => Status::Done,
                    'message' => 'Imported successfully'
                ]);
                DB::commit();

            } else {
                DB::rollBack();
                // Some rows failed, but import continued
                $formattedErrors = $this->formatErrors($errors);

                ImportStatus::find($this->importId)->update([
                    'status' => Status::Failed,
                    'message' => $formattedErrors
                ]);

                Log::warning('User import failed', [
                    'file' => $this->filePath,
                    'errors' => $errors,
                ]);
            }

            Storage::delete($this->filePath);

        } catch (\Exception $e) {
            // Catastrophic failure that prevented the entire import
            $errorMessage = $this->formatCatastrophicError($e);

            ImportStatus::find($this->importId)->update([
                'status' => Status::Failed,
                'message' => $errorMessage,
            ]);

            Log::error('User import completely failed', [
                'file' => $this->filePath,
                'error' => $errorMessage,
            ]);

        }
    }

    private function formatErrors(array $errors): string
    {
        $formattedErrors = array_map(function ($error) {
            if (is_array($error)) {
                return sprintf(
                    "Row data: %s, Error: %s",
                    json_encode($error['row']),
                    implode("",$error['errors'])
                );
            }
            return (string) $error;
        }, $errors);

        return nl2br(implode(PHP_EOL, $formattedErrors)) ;
    }

    /**
     * Format catastrophic import errors
     *
     * @param Exception $e
     * @return string
     */
    private function formatCatastrophicError(Exception $e): string
    {
        // Customize error message for system-level failures
        return sprintf(
            "Import failed: %s\nFile: %s\nLine: %d",
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );
    }
}
