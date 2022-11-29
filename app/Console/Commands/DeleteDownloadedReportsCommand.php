<?php

namespace App\Console\Commands;

use App\EmployeeReportStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteDownloadedReportsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:downloaded-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes downloaded reports.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = Storage::files('reports/');
        foreach ($files as $file) {
            $fileName = explode("reports/", $file)[1];
            $fileStatus = EmployeeReportStatus::where('pdf_file_name', $fileName)->exists();
            if (!$fileStatus) {
                Storage::delete($file);
            }

        }

    }
}
