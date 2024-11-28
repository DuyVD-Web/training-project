<?php

namespace App\Jobs;

use App\Imports\UsersImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProcessImportUsers implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    public $timeout = 300;
    public $tries = 3;
    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $import = new UsersImport();
            Excel::import($import, $this->filePath);

            Log::info('Users imported successfully', [
                'file' => $this->filePath,
            ]);
            Storage::delete($this->filePath);
        } catch (\Exception $e) {
            Log::error('User import failed', [
                'file' => $this->filePath,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
