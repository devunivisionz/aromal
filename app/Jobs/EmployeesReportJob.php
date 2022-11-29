<?php

namespace App\Jobs;

use App\Employee;
use App\EmployeeReportStatus;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PDF;
use Exception;

class EmployeesReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $startDate;
    protected $endDate;
    protected $employees;
    protected $reportId;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 1200;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $retryAfter = 1230;

    /**
     * Create a new job instance.
     *
     * @param $startDate
     * @param $endDate
     * @param $employees
     */
    public function __construct($startDate, $endDate, $employees, $reportId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->employees = $employees;
        $this->reportId = $reportId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        ini_set("max_execution_time", "-1");

        $selectedEmployees = Employee::select('emp_records.id', 'emp_name', 'emp_code', 'dept_name', 'date_of_joining', 'date_of_resignation', 'status_active')
            ->join('emp_departments', 'emp_departments.id', 'emp_records.emp_department_id')
            ->whereIn('emp_records.id', $this->employees)
            ->orderBy('date_of_joining', 'DESC')
            ->get();

        $employeeReportData = collect([]);

        foreach ($selectedEmployees as $employee) {
            $employeeReportStatus = EmployeeReportStatus::create([
                'report_id' => $this->reportId,
                'emp_id' => $employee->id,
                'emp_name' => $employee->emp_name,
                'status' => 'processing'
            ]);

            try {
                $employeeData = ReportService::getEmployeeAttendanceData($this->startDate, $this->endDate, $employee);
                $employeeData['loginData'] = ReportService::getEmployeeTaskData($employeeData['loginData'], $employee->emp_code);
                $employeeData['personalData']['totalHoursWorked'] = $employeeData['loginData']['taskHours'];
                $employeeReportData->push($employeeData);
            } catch (Exception $e) {
                Log::error(["EmployeesReportJob::handle()", "Error in getting employee data.", "Exception: ", $e->getMessage(), $e->getTrace()]);
            }

            $employeeReportStatus->update(['status' => 'completed']);
        }

        try {
            /*Log::debug([
                "EmployeesReportJob::handle()",
                "Employee Report Data",
                $employeeReportData
            ]);*/
            EmployeeReportStatus::where('report_id', $this->reportId)->update(['pdf_status' => 'processing']);

            $pdf = PDF::loadView('reports/pdf', [
                'result' => $employeeReportData,
                'startDate' => Carbon::parse($this->startDate)->format('M jS Y'),
                'endDate' => Carbon::parse($this->endDate)->format('M jS Y')
            ])->setPaper('a4', 'portrait');
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
             $canvas = $dom_pdf->get_canvas();
             $canvas->page_text(250, 780, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
             $canvas->page_text(50, 797, "Confidential Report - All Rights Reserved Carmatec IT Solutions Pvt Ltd.", null, 15, array(0, 0, 0));
             $canvas->page_text(217, 820, "This is a computer generated report.", null, 8, array(0, 0, 0));
            $fileName = 'VC-Report-(' . (Carbon::parse($this->startDate)->format('d-M-Y')) . ')-(' . (Carbon::parse($this->endDate)->format('d-M-Y')) . ')-' . count($this->employees) . '-Employees.pdf';
            $output = $pdf->download($fileName);

            Storage::put('reports/' . $fileName, $output->__toString(), 'public');

            EmployeeReportStatus::where('report_id', $this->reportId)->update(['pdf_created' => 1, 'pdf_file_name' => $fileName, 'pdf_status' => 'generated']);
        } catch (Exception $e) {
            EmployeeReportStatus::where('report_id', $this->reportId)->update(['status' => 'failed', 'pdf_status' => 'failed']);
            Log::error(["EmployeesReportJob::handle()", "Error in creating report.", "Exception: ", $e->getMessage(), $e->getTrace()]);
        }

        // Delete downloaded files.
        // try {
        //     Artisan::call('delete:downloaded-reports');
        // } catch (Exception $e) {
        //     Log::error(["EmployeesReportJob::handle()", "Error in deleting old reports.", "Exception: ", $e->getMessage(), $e->getTrace()]);
        // }
    }

    /**
     * Handle a job failure.
     *
     * @param  $exception
     * @return void
     */
    public function failed($exception)
    {
        // Send user notification of failure, etc...
        EmployeeReportStatus::where('report_id', $this->reportId)->update(['status' => 'failed']);
        Log::error(["EmployeesReportJob::handle()", "Error in creating report.", "Exception: ", $exception->getMessage(), $exception->getTrace()]);
    }
}
