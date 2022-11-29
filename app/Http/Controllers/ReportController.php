<?php

namespace App\Http\Controllers;

use App\EmployeeReportStatus;
use App\Http\Controllers\Controller;
use App\Employee;
use App\Http\Requests\ReportSearchRequest;
use App\Jobs\EmployeesReportJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use PDF;
use App\Services\ReportService;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::getData();

        return view('reports.index', compact('employees'));
    }

    public function search(ReportSearchRequest $request)
    {
        $reportId = Carbon::now()->timestamp . rand();
        $startDate = $request['fromdate'];
        $endDate = $request['todate'];
        $employees = explode(",", $request['employee']);
        
        $selectedEmployees = Employee::select('emp_records.id', 'emp_name', 'emp_code', 'dept_name', 'date_of_joining', 'date_of_resignation','status_active')
            ->join('emp_departments', 'emp_departments.id', 'emp_records.emp_department_id')
            ->whereIn('emp_records.id', $employees)
            ->orderBy('date_of_joining', 'DESC')
            ->get();

        $employeeReportData = collect([]);
        
        foreach ($selectedEmployees as $employee) {
            $employeeReportStatus = EmployeeReportStatus::create([
                'report_id' => $reportId,
                'emp_id' => $employee->id,
                'emp_name' => $employee->emp_name,
                'status' => 'processing'
            ]); 

            try {
                $employeeData = ReportService::getEmployeeAttendanceData($startDate, $endDate, $employee);
                $employeeData['loginData'] = ReportService::getEmployeeTaskData($employeeData['loginData'], $employee->emp_code);
                $employeeData['personalData']['totalHoursWorked'] = $employeeData['loginData']['taskHours'];
                $employeeData['ips'] = \Config::get('constants.ips');
                $employeeData['type'] = $request->type;
                $employeeData['sections'] = (($request->sections)) ?? [];
                //dd($employeeData);
                $employeeReportData->push($employeeData);
            } catch (Exception $e) {
                Log::error(["EmployeesReportJob::handle()", "Error in getting employee data.", "Exception: ", $e->getMessage(), $e->getTrace()]);
            }

            $employeeReportStatus->update(['status' => 'completed']);
        }

        try {
            EmployeeReportStatus::where('report_id', $reportId)->update(['pdf_status' => 'processing']);
            
            $pdf = PDF::loadView('reports/pdf', [
                'result' => $employeeReportData,
                'startDate' => Carbon::parse($startDate)->format('M jS Y'),
                'endDate' => Carbon::parse($endDate)->format('M jS Y')
            ])->setPaper('a4', 'portrait');
            $pdf->output();
            // $dom_pdf = $pdf->getDomPDF();
            // $canvas = $dom_pdf->get_canvas();
            // $canvas->page_text(250, 780, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
            // $canvas->page_text(50, 797, "Confidential Report - All Rights Reserved Carmatec IT Solutions Pvt Ltd.", null, 15, array(0, 0, 0));
            // $canvas->page_text(217, 820, "This is a computer generated report.", null, 8, array(0, 0, 0));
            Log::info('PDF REPORTS');
            $fileName = 'VC-Report-(' . (Carbon::parse($startDate)->format('d-M-Y')) . ')-(' . (Carbon::parse($endDate)->format('d-M-Y')) . ')-' . count($employees) . '-Employees.pdf';
            Log::info($fileName);
            Log::info("Hey Kanav Sharma");

            $output = $pdf->download($fileName);
            Storage::put('reports/' . $fileName, $output->__toString(), 'public');

            EmployeeReportStatus::where('report_id', $reportId)->update(['pdf_created' => 1, 'pdf_file_name' => $fileName, 'pdf_status' => 'generated']);
        } catch (Exception $e) {
            EmployeeReportStatus::where('report_id', $reportId)->update(['status' => 'failed', 'pdf_status' => 'failed']);
            Log::error(["EmployeesReportJob::handle()", "Error in creating report.", "Exception: ", $e->getMessage(), $e->getTrace()]);
        }

        //$reportId = Carbon::now()->timestamp . rand();
        //dispatch(new EmployeesReportJob($request['fromdate'], $request['todate'], explode(",", $request['employee']), $reportId));
        $inputDisabled = true;
        $employees = Employee::getData();
        return view('reports.index', compact('employees', 'inputDisabled', 'reportId'));
    }

    public function exportPDF(ReportSearchRequest $request)
    {
        /*$emp = explode(',', $request['employee']);
        $constraints = [
            'fromdate' => $request['fromdate'],
            'todate' => $request['todate'],
            'employee' => $emp
        ];
        $employees = ReportService::getData($constraints);

        $pdf = PDF::loadView('reports/pdf', ['result' => $employees])->setPaper('a4', 'landscape');

        return $pdf->download('VC-Report-(' . (Carbon::parse($constraints['fromdate'])->format('d-F-Y')) . ')-(' . (Carbon::parse($constraints['todate'])->format('d-F-Y')) . ')-' . count($constraints['employee']) . '-Employees.pdf');*/

    }

    public function sendmail()
    {
        $data["email"] = "aatmaninfotech@gmail.com";
        $data["title"] = "From ItSolutionStuff.com";
        $data["body"] = "This is Demo";

        $pdf = PDF::loadView('reports/pdf', $employees);

        Mail::send('email.report', $data, function ($message) use ($data, $pdf) {
            $message->to($data["email"], $data["email"])
                ->subject($data["title"])
                ->attachData($pdf->output(), "text.pdf");
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)

    {


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        //$package =Package::find($id);
        //$package->delete();
        //return redirect()->intended('/admin/package');

    }

    public function status($reportId)
    {
        $reportStatusData = EmployeeReportStatus::select('emp_id', 'emp_name', 'pdf_created', 'status', 'pdf_status')->where('report_id', $reportId)->get();

        if ($reportStatusData->where('status', 'processing')->count()) {
            $response = $reportStatusData->where('status', 'processing')->first();
        } else {
            $response = $reportStatusData->last();
        }

        return response()->json($response);
    }

    public function download($reportId)
    {
        $reportStatusData = EmployeeReportStatus::select('pdf_file_name')->where('report_id', $reportId)->first();
        $fileName = $reportStatusData->pdf_file_name;
        $filePath = 'reports/' . $fileName;
        if (Storage::exists($filePath)) {
            $response = response(Storage::get($filePath), 200, [
                'Content-Type' => '.pdf',
                'Content-Length' => Storage::size($filePath),
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => "attachment; filename={$fileName}",
                'Content-Transfer-Encoding' => 'binary',
            ]);
            EmployeeReportStatus::where('report_id', $reportId)->delete();
            unlink(storage_path('app/'.$filePath));
            return $response;
        } else {
            Log::debug([
                "ReportController::download()",
                "File does not exist: ",
                $filePath
            ]);
        }
        return redirect()->route('reports.index')->withErrors('Error in downloading the report');
    }


}
