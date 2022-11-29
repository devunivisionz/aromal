<?php

namespace App\Services;

use App\Employee;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    const DATE_FORMAT = 'M jS Y - D';
    const NOT_AVAILABLE = 'NA';
    const YES = 'Yes';
    const NO = 'No';
    const MAX_DAILY_HOURS = 8;

    public static function getEmployeeAttendanceData($startDate, $endDate, $employee): array
    {
        $databases = DatabaseService::getRequiredDatabasesConnections($startDate, $endDate);
        
        $result = collect([]);
        $finalResult = collect([]);
        $leavesData = collect([]);
        // Query across various databases as per the start and end date
        foreach ($databases as $db) {
            $thisResult = DB::connection($db)->table('visioncarma_loginlogs')
                ->select(['login_datetime', 'logout_datetime','machine_ip','tech_id'])
                ->WHERE('tech_id', $employee->emp_code)
                ->whereRaw("DATE(visioncarma_loginlogs.login_datetime) BETWEEN ? AND ?", [$startDate, $endDate])
                ->get()
                ->toArray();
            DB::disconnect($db);
            $result = $result->concat($thisResult);


            $queryResult = DB::connection($db)->table('visioncarma_loginlogs')
                ->select('machine_ip',DB::raw('count(machine_ip) as total'))
                ->WHERE('tech_id', $employee->emp_code)
                ->whereRaw("DATE(visioncarma_loginlogs.login_datetime) BETWEEN ? AND ?", [$startDate, $endDate])
                ->groupBy('machine_ip')
                ->get()
                ->toArray();
            DB::disconnect($db);
            $finalResult = $finalResult->concat($queryResult);
            
            $leaveRequests = DB::connection($db)->table('leave_requests')
                ->where('tech_id', $employee->emp_code)
                ->where('from_date','>=',$startDate)
                ->where('to_date','<=',$endDate)
                ->get()
                ->toArray();
            DB::disconnect($db);
            $leavesData = $leavesData->concat($leaveRequests);
        }

        $employeeData = [];

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $dateOfJoining = Carbon::parse($employee->date_of_joining);
        $dateOfRelieving = 'NA';

        // Calculations should be done from date of joining if the start-date is earlier
        if ($dateOfJoining->gt($startDate)) {
            $startDate = $dateOfJoining;
        }
        $dateOfJoining = $dateOfJoining->format('M jS Y');

        $empResignedrecords = DB::table('emp_resignedrecords')->where('emp_code',$employee->emp_code)->first();

        if ($employee->date_of_resignation) {
            $dateOfRelieving = Carbon::parse($employee->date_of_resignation);
            // Calculations should be done till date of resignation if the end-date is later
            if ($dateOfRelieving->lt($endDate)) {
                $endDate = $dateOfRelieving;
            }
            $dateOfRelieving = $dateOfRelieving->format('M jS Y');
            
        }
        if($empResignedrecords){
            $endDate = Carbon::parse($empResignedrecords->date_of_relieving);
            $dateOfRelieving = Carbon::parse($empResignedrecords->date_of_relieving)->format('M jS Y');
        }

        if ($endDate->gte($startDate)) {
            $totalWorkingDays = $startDate->diffInDaysFiltered(function (Carbon $date) {
                return !$date->isWeekend();
            }, $endDate);
        } else {
            $totalWorkingDays = 0;
        }

        $employeeData['personalData'] = [
            'id' => $employee->id,
            'dept_name' => $employee->dept_name,
            'code' => $employee->emp_code,
            'name' => $employee->emp_name,
            'joiningDate' => $dateOfJoining,
            'relievingDate' => $dateOfRelieving,
            'status' => Employee::getStatus($employee->status_active),
            'totalWorkingDays' => $totalWorkingDays,
            'totalWorkingHours' => $totalWorkingDays * 8,
            'machine_ips' => $finalResult,
            'leavesData' => $leavesData
        ];

        $result = $result->groupBy(function ($data) {
            return Carbon::parse($data->login_datetime)->toDateString();
        });

        $employeeData['personalData']['totalDaysWorked'] = $totalWorkingDays ? $result->count() : 0;

        $result = $result->map(function ($data) {
            $valHavingLogin = $data->whereNotNull('login_datetime')->first();
            $valHavingLogout = $data->whereNotNull('logout_datetime')->first();
            if ($valHavingLogout) {
                $logoutTime = Carbon::parse($valHavingLogout->logout_datetime)->format('h:i:s A');
                if ($valHavingLogout->login_datetime) {
                    $loginDateTime = $valHavingLogout->login_datetime;
                    $date = Carbon::parse($loginDateTime)->toDateString();
                    $loginDate = str_replace("-", " <br> ", Carbon::parse($loginDateTime)->format(static::DATE_FORMAT));
                    $loginTime = Carbon::parse($loginDateTime)->format('h:i:s A');
                } else {
                    if ($valHavingLogin) {
                        $loginDateTime = $valHavingLogin->login_datetime;
                        $date = Carbon::parse($loginDateTime)->toDateString();
                        $loginDate = str_replace("-", " <br> ", Carbon::parse($loginDateTime)->format(static::DATE_FORMAT));
                        $loginTime = Carbon::parse($loginDateTime)->format('h:i:s A');
                    } else {
                        $date = static::NOT_AVAILABLE;
                        $loginDate = static::NOT_AVAILABLE;
                        $loginTime = static::NOT_AVAILABLE;
                    }
                }
            } else {
                $logoutTime = static::NOT_AVAILABLE;
                if ($valHavingLogin) {
                    $loginDateTime = $valHavingLogin->login_datetime;
                    $date = Carbon::parse($loginDateTime)->toDateString();
                    $loginDate = str_replace("-", " <br> ", Carbon::parse($loginDateTime)->format(static::DATE_FORMAT));
                    $loginTime = Carbon::parse($loginDateTime)->format('h:i:s A');
                } else {
                    $date = static::NOT_AVAILABLE;
                    $loginDate = static::NOT_AVAILABLE;
                    $loginTime = static::NOT_AVAILABLE;
                }
            }

            return [
                'date' => $date,
                'loginDate' => $loginDate,
                'login' => $loginTime,
                'logout' => $logoutTime
            ];
        });

        $employeeData['loginData'] = $result->sortBy('date')->groupBy(function ($data) {
            return $data['date'] == static::NOT_AVAILABLE ? $data['date'] : Carbon::parse($data['date'])->format("F, Y");
        });

        return $employeeData;
    }

    public static function getEmployeeTaskData(Collection $employeeAttendanceData, string $employeeCode): array
    {
        $hours = 0;
        $taskData = $employeeAttendanceData->map(function ($data) use ($employeeCode, &$hours){
            $loginDates = $data->pluck('date');
            $databases = DatabaseService::getRequiredDatabasesConnections($loginDates->min(), $loginDates->max());
            $employeeTaskData = collect([]);
            // Query across various databases as per the start and end date
            foreach ($databases as $db) {
                $thisResult = DB::connection($db)->table('workdelegation_task_techs')
                    ->join('workdelegation_tasks', function($join) use ($loginDates, $db) {
                        $join->on('workdelegation_tasks.id', 'workdelegation_task_techs.workdelegation_task_id')
                            ->whereRaw('FIND_IN_SET(DATE(workdelegation_tasks.start_date), ?)', [implode(",", $loginDates->toArray())]);
                    })
                    ->where('workdelegation_task_techs.tech_id', $employeeCode)
                    ->selectRaw(
                        "workdelegation_tasks.id AS task_id,
                        workdelegation_tasks.task_name AS task_name,
                        workdelegation_tasks.description AS description,
                        workdelegation_tasks.start_date AS task_start_date_time,
                        workdelegation_tasks.end_date AS task_end_date_time,
                        DATE(workdelegation_tasks.start_date) AS task_start_date"
                    )
                    ->get()
                    ->toArray();

                DB::disconnect($db);
                $employeeTaskData = $employeeTaskData->concat($thisResult);
            }

            $rejectedTasks = [];
            $data = $data->map(function ($thisData) use ($employeeTaskData, &$hours, &$rejectedTasks) {
                $dailyTaskData = $employeeTaskData->where('task_start_date', $thisData['date']);
                if ($dailyTaskCount = $dailyTaskData->count()) {
                    $thisData['taskCount'] = $dailyTaskCount;
                    $thisData['hours'] = $dailyTaskData->sum(function ($data) {
                        return Carbon::parse($data->task_end_date_time)->diffInHours($data->task_start_date_time);
                    });
                } else {
                    $thisData['taskCount'] = 0;
                    $thisData['hours'] = 0;
                }

                // If this is uncommented then the no. of hours is calculated by the diff between logout time and login time in the absence of daily report.
                /*if ($thisData['hours'] == 0 && $thisData['logout'] && $thisData['login'] && $thisData['logout']  != static::NOT_AVAILABLE && $thisData['login'] != static::NOT_AVAILABLE) {
                    $thisData['hours'] = Carbon::parse($thisData['logout'])->diffInHours($thisData['login']);
                }*/

                if ($thisData['taskCount'] > 1) {
                    $thisData['notes'] = $thisData['taskCount'] . ' tasks, <br>';
                    $thisData['notes'] .= $thisData['hours'] > 1 ? $thisData['hours'] . ' hours.' : $thisData['hours'] . ' hour.';
                } else {
                    $thisData['notes'] = 'Invalid <br> timesheet.';
                }

                /*$thisData['notes'] = $thisData['taskCount'] > 1 ? $thisData['taskCount'] . ' tasks, ' : $thisData['taskCount'] . ' task, ';

                $thisData['notes'] .= $thisData['hours'] > 1 ? $thisData['hours'] . ' hours.' : $thisData['hours'] . ' hour.';*/

                $thisData['hours'] = $thisData['hours'] > static::MAX_DAILY_HOURS ? static::MAX_DAILY_HOURS : $thisData['hours'];

                if ($dailyTaskCount > 1) {
                    $hours += $thisData['hours'];
                    $thisData['dailyReport'] = static::YES;
                } else {
                    // Add this day to rejected list
                    $thisTask = $dailyTaskData->first();
                    $rejectedTask['date'] = str_replace("-", " <br> ", Carbon::parse($thisData['date'])->format(static::DATE_FORMAT));
                    if ($dailyTaskCount) {
                        $startTime = Carbon::parse($thisTask->task_start_date_time);
                        $endTime = Carbon::parse($thisTask->task_end_date_time);
                        $rejectedTask['name'] = $thisTask->task_name;
                        $rejectedTask['entryTime'] = $startTime->format('h:i:s A');
                        $rejectedTask['exitTime'] = $endTime->format('h:i:s A');
                        $rejectedTask['details'] = $thisTask->description ? $thisTask->description : static::NOT_AVAILABLE;
                        $stime = strtotime($startTime->format('h:i:s A'));
                        $etime =  strtotime($endTime->format('h:i:s A'));
                        $difference = round(abs($etime - $stime) / 3600,0);
                        $rejectedTask['hours'] = $difference;
                    } else {
                        $rejectedTask['name'] = 'No task name';
                        $rejectedTask['entryTime'] = 'No task start time';
                        $rejectedTask['exitTime'] = 'No task end time';
                        $rejectedTask['details'] = 'Invalid task';
                        $rejectedTask['hours'] = 'No hours specified';
                    }
                    $rejectedTasks[] = $rejectedTask;
                    $thisData['dailyReport'] = static::NO;
                }
                return $thisData;
            })->sortBy('date');

            return collect([
                'tasks' => $data,
                'rejectedTasks' => $rejectedTasks
            ]);
        });

        return [
            'taskData' => $taskData,
            'taskHours' => $hours
        ];
    }
}
