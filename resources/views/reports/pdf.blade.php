@php date_default_timezone_set("Asia/Calcutta");  @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: solid 2px;
            padding: 10px 5px;
        }

        tr {
            text-align: center;
        }

        .container {
            width: 100%;
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }

        .page-footer {
            position: fixed;
            left: 0;
            bottom: 2em;
            width: 100%;
            text-align: center;
        }

        .month {
            text-align: center;
        }
    </style>
</head>
<body style="font-family: Calibri, sans-serif;">
@foreach($result as $employee)
        <h2 style="text-align: center; top: 0;">
            Work Report for <span>{{ $employee['personalData']['name'] }}</span>
        </h2>
        <div class="container">
            @if($employee['type'] == "detailed" || ($employee['type'] == "simple" && in_array("employee_details",$employee['sections'])))
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>
                                Employee ID
                            </th>
                            <td>
                                {{ $employee['personalData']['code'] }}
                            </td>
                            <th>
                                Employee Status
                            </th>
                            <td>
                                {{ $employee['personalData']['status'] }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Date Of Joining
                            </th>
                            <td>
                                {{ $employee['personalData']['joiningDate'] }}
                            </td>
                            <th>
                                Date Of Relieving
                            </th>
                            <td>
                                {{ $employee['personalData']['relievingDate'] }}
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
            @endif
            @if(count($employee['personalData']['leavesData']))
                @if($employee['type'] == "detailed" || ($employee['type'] == "simple" && in_array("leaves_summary
                ",$employee['sections'])))
                    
                    @php 
                        $approved = 0;
                        $unApproved = 0;
                    @endphp
                    @foreach($employee['personalData']['leavesData'] as $key => $value)
                        @if($value->approval_status == "Y")
                            @php $approved++; @endphp
                        @else
                            @php $unApproved++; @endphp
                        @endif
                    @endforeach
                    <div style="background-color: lightgrey;">
                        <h3>Leaves Summary</h3>
                    </div>
                    <div>
                        <table>
                            <thead>
                                <tr>
                                    <th>
                                        # No Of Leave Apply
                                    </th>
                                    <td>
                                        {{ count($employee['personalData']['leavesData']) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        # No Of Leave Approved
                                    </th>
                                    <td>
                                        {{ $approved }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        # No Of Leave Unapproved
                                    </th>
                                    <td>
                                        {{ $unApproved }}
                                    </td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                
                    <div class="container">
                        <h3 class="month">Leaves Requests</h3>
                        <table id="example2" role="grid">
                            <thead>
                                <tr role="row">
                                    <th>S. No.</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Status</th>
                                    <th>No Of Days</th>
                                    <th>Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee['personalData']['leavesData'] as $key => $value)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ date('d M Y',strtotime($value->from_date)) }}</td>
                                    <td>{{ date('d M Y',strtotime($value->to_date)) }}</td>
                                    <td>{{ ($value->approval_status == "Y") ? "Approved" : "UnApproved" }}</td>
                                    <td>{{ $value->total_days }}</td>
                                    <td>{{ $value->reason_for_leave }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
            @if($employee['type'] == "detailed" || ($employee['type'] == "simple" && in_array("report_period",$employee['sections'])))
            <div style="background-color: lightgrey;">
                <h3>
                    Report for the duration {{ $startDate }} to {{ $endDate }}
                </h3>
            </div>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>
                                # of working days
                            </th>
                            <td>
                                {{$employee['personalData']['totalWorkingDays']}}
                            </td>
                            <th>
                                Total hours to work
                            </th>
                            <td>
                                {{$employee['personalData']['totalWorkingHours']}}
                            </td>
                        </tr>
                        <tr>
                            @php
                                $validTime = "10:15:00";
                                $totalDays = 0;
                            @endphp
                            
                            @foreach($employee['loginData']['taskData'] as $key => $data)
                            @php
                                //$totalDays = 0;
                            @endphp
                            @foreach($data['tasks'] as $loginData)
                                @if(date('H:i:s', strtotime($loginData['login'])) <=  $validTime)
                                    @php $totalDays++; @endphp
                                @endif
                            @endforeach
                            @endforeach
                            <th>
                                # of days present {{ date("h:i") }}
                            </th>
                            <td>
                                {{ $totalDays }}
                            </td>
                            <th>
                                Total hours logged
                            </th>
                            <td>
                                {{ $employee['personalData']['totalHoursWorked'] }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                % of days present
                            </th>
                            <td>
                                {{ $employee['personalData']['totalWorkingDays'] ? number_format((($totalDays / ($employee['personalData']['totalWorkingDays'])) * 100), 2) : 0 }}
                            </td>
                            <th>
                                % hours logged
                            </th>
                            <td>
                                {{ $employee['personalData']['totalWorkingHours'] ? number_format((($employee['personalData']['totalHoursWorked'] / ($employee['personalData']['totalWorkingHours'])) * 100), 2) : 0 }}
                            </td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            @endif
            <div class="container">
                @foreach($employee['loginData']['taskData'] as $key => $data)
                    @if($employee['type'] == "detailed" || ($employee['type'] == "simple" && in_array("summary_section",$employee['sections'])))
                    <h3 class="month">{{ $key}}</h3>
                    <table id="example2" role="grid">
                        <thead>
                            <tr role="row">
                                <th>S. No.</th>
                                <th width="18%">Date</th>
                                <th>Login Time</th>
                                <th>Logout Time</th>
                                <th>Hours At Work</th>
                                <th>Daily Report Available</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: small">
                            @php
                                $sNo = 1;
                            @endphp
                            @foreach($data['tasks'] as $loginData)
                                @if(date('H:i:s', strtotime($loginData['login'])) <=  $validTime)
                                    <tr>
                                        <td>{{ $sNo++ }}</td>
                                        {{--<td>
                                            @php
                                                $loginDate = explode("-", $loginData['loginDate'])
                                            @endphp
                                            {{$loginDate[0]}}
                                            <br>
                                            {{$loginDate[1]}}
                                        </td>--}}
                                        <td>{!! $loginData['loginDate'] !!}</td>
                                        <td>{{$loginData['login']}}</td>
                                        <td>{{$loginData['logout']}}</td>
                                        <td>{{$loginData['hours']}}</td>
                                        <td>{{$loginData['dailyReport']}}</td>
                                        <td>{!! $loginData['notes'] !!}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Total Hours</th>
                                <th>Total Hours(%)</th>
                                <th>Total Tasks Logged</th>
                                <th>Daily Reports</th>
                                <th>Daily Reports(%)</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: small">
                            <tr>
                                @if($data['tasks']->count('date'))
                                    <td>{{ $data['tasks']->where('dailyReport', 'Yes')->sum('hours') }}</td>
                                    <td>{{ number_format((($data['tasks']->where('dailyReport', 'Yes')->sum('hours') / ($data['tasks']->count('date') * 8)) * 100), 2) }}</td>
                                    <td>{{ $data['tasks']->where('dailyReport', 'Yes')->sum('taskCount') }}</td>
                                    <td>{{ $data['tasks']->where('dailyReport', 'Yes')->count() }}</td>
                                    <td>{{ number_format((($data['tasks']->where('dailyReport', 'Yes')->count() / ($data['tasks']->count('date'))) * 100), 2)}}</td>
                                @else
                                    <td>{{ $data['tasks']->where('dailyReport', 'Yes')->sum('hours') }}</td>
                                    <td>0.00</td>
                                    <td>{{ $data['tasks']->where('dailyReport', 'Yes')->sum('taskCount') }}</td>
                                    <td>{{ $data['tasks']->where('dailyReport', 'Yes')->count() }}</td>
                                    <td>0.00</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                    @endif
                    @if(count($data['rejectedTasks']))
                        @if($employee['type'] == "detailed" || ($employee['type'] == "simple" && in_array("work_summary",$employee['sections'])))
                            <div style="background-color: lightgrey;">
                                <h4>Rejected Tasks</h4>
                            </div>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S. No.</th>
                                        <th width="18%">Date</th>
                                        <th>Tasks</th>
                                        <th>Sign In time</th>
                                        <th>Sign off time</th>
                                        <th>Hours</th>
                                        <th>Details</th>
                                        <th>Present</th>
<!--                                        <th>Observations</th>-->
                                    </tr>
                                </thead>
                                <tbody style="font-size: small">
                                    @php
                                        $sNo = 1;
                                    @endphp
                                    @foreach($data['rejectedTasks'] as $rejectedTask)
                                        <tr>
                                            <td>{{$sNo++}}</td>
                                            <td>{!! $rejectedTask['date'] !!}</td>
                                            <td>{{$rejectedTask['name']}}</td>
                                            <td>{{$rejectedTask['entryTime']}}</td>
                                            <td>{{$rejectedTask['exitTime']}}</td>
                                            <td>{{$rejectedTask['hours']}}</td>
                                            <td>{{$rejectedTask['details']}}</td>
                                            <td>{{($rejectedTask['hours'] == 0 || $rejectedTask['hours'] == "No hours specified") ? 'No' : 'Yes'}}</td>
<!--                                            <th>{{($rejectedTask['name'] == "No task name" || $rejectedTask['hours'] == "No hours specified" || $rejectedTask['hours'] == 0) ? 'Yes' : 'No'}}</th>-->
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @endif
                    <hr>
                @endforeach
                @if(count($employee['personalData']['machine_ips']))
                @if($employee['type'] == "detailed" || ($employee['type'] == "simple" && in_array("ip_summary",$employee['sections'])))
                    <div style="background-color: lightgrey;">
                        <h4>IP summary</h4>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S. No.</th>
                                <th>IP</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: small">
                            @php
                                $sNo = 1;
                            @endphp
                            @foreach($employee['personalData']['machine_ips'] as $machine_ips)
                                <tr>
                                    <td>{{$sNo++}}</td>
                                    <td>{{  $machine_ips->machine_ip }}</td>
                                    <td>{{ $machine_ips->total }}</td>
                                    <td>{{ (in_array($machine_ips->machine_ip, $employee['ips'])) ? 'Yes' : 'No' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endif
            <hr>
            </div><div class="page-break"></div></div>@endforeach</body></html>

