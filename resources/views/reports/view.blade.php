@extends('layouts.pagelayout')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('reports.pdf') }}">
                        {{ csrf_field() }}
                        <input type="hidden" value="{{$constraints['fromdate']}}" name="fromdate" />
                        <input type="hidden" value="{{$constraints['todate']}}" name="todate" />
                        <input type="hidden" value="{{$constraints['employee']}}" name="employee" />
                        <button type="submit" class="btn btn-info">
                            Export to PDF
                        </button>
                    </form>
                    <!-- general form elements -->
                    @foreach($employeeReportData as $employee)

                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Employee Attendance Report</h3>
                                <div class="col-sm-4">
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->

                            <div class="card-body">
                                <div class='row'>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="newsTitle">Employee ID</label> : {{$employee['personalData']['code']}}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="newsTitle">Employee Name</label> : {{$employee['personalData']['name']}}</div>
                                    </div>

                                </div>
                                <div class='row'>
                                    <div class="col-md-6">
                                        <div class="form-group"><label for="newsTitle">JoiningDate</label>
                                            : {{$employee['personalData']['joiningDate']}}
                                        </div>
                                    </div>$employ['joiningDate']
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="newsTitle">Status</label>
                                            : {{$employee['personalData']['status']}}
                                        </div>
                                    </div>
                                    {{--                                </div>--}}
                                    {{--                            </div>--}}
                                    {{--                            <div class="card-body">--}}
                                    {{--                                <div class='row'>--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="newsTitle">Total days worked</label>
                                            : {{$employee['personalData']['totalDaysWorked']}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="newsTitle">Total days to work</label>
                                            : {{$employee['personalData']['totalWorkingDays']}}</div>
                                    </div>

                                </div>
                                <div class='row'>
                                    <div class="col-md-6">
                                        <div class="form-group"><label for="newsTitle">Total logged </label> :
                                            : {{$employee['personalData']['totalHoursWorked']}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="newsTitle">Total hours to work</label>
                                            : {{$employee['personalData']['totalWorkingHours']}}</div>
                                    </div>
                                </div>
                                {{--                            </div>--}}
                                {{--                            <div class="card-body">--}}
                                <div class="container">
                                    @foreach($employee['loginData']['taskData'] as $key => $data)
                                        <h4>{{$key}}</h4>
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Login Time</th>
                                                <th>Logout time</th>
                                                <th>Hours</th>
                                                <th>Notes</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data as $loginData)
                                                    <tr>
                                                        <td>{{$loginData['loginDate']}}</td>
                                                        <td>{{$loginData['login']}}</td>
                                                        <td>{{$loginData['logout']}}</td>
                                                        <td>{{$loginData['hours']}}</td>
                                                        <td>
                                                            @if($loginData['taskCount'] == 0)
                                                                No task logged
                                                            @elseif($loginData['taskCount'] == 1)
                                                                Across how many tasks?
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Total Hours</th>
                                                    <th>Total Hours(%)</th>
                                                    <th>Total Tasks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $data->sum('hours') }}</td>
                                                    <td>{{ number_format((($data->sum('hours') / ($data->count('date') * 8)) * 100), 2) }}</td>
                                                    <td>{{ $data->sum('taskCount') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

@endsection





