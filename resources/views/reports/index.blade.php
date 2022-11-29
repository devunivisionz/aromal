@extends('layouts.pagelayout')

@section('content')
<style>
    #loaderDiv{
    width:100%;
    height:auto;
    background-color: #887f7f;
    position: relative;
}
.loader{
    position: absolute;
    top:0px;
    right:0px;
    width:100%;
    height:100%;
    background-color:#eceaea;
    background-image:url('https://www.5balloons.info/wp-content/uploads/2020/05/ajax-loader.gif');
    background-size: 50px;
    background-repeat:no-repeat;
    background-position:center;
    z-index:10000000;
    opacity: 0.4;
    filter: alpha(opacity=40);
}
</style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                @if ($errors->any())
                    <div>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li style="color: red;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="portlet box blue">  <!-- to b closed-->
                        <div class="portlet-title">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="box">
                <div class="box-header">
                    Report
                </div>
                <!-- /.box-header -->

                <!-- /.box-body -->
                <form method="POST" action="{{ route('reports.search') }}">
                    {{ csrf_field() }}
                    <div class="box box-default" id="loaderDiv">
                         <div id="loader"></div> 
                        <div class="box-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="col-md-12">
                                                        <label>From Date</label>
                                                        <div class="input-group date">
                                                            @if(!empty($inputDisabled) && $inputDisabled)
                                                                <input type="date" value="{{ old('invoicedate') }}"
                                                                       name="fromdate" class="form-control pull-right"
                                                                       id="fromDate" disabled>
                                                            @else
                                                                <input type="date" value="{{ old('invoicedate') }}"
                                                                       name="fromdate" class="form-control pull-right"
                                                                       id="fromDate">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="col-md-12">
                                                        <label class="control-label">To Date</label>
                                                        <div class="input-group date">
                                                            @if(!empty($inputDisabled) && $inputDisabled)
                                                                <input type="date" value="{{ old('invoicedate') }}"
                                                                       name="todate" class="form-control pull-right"
                                                                       id="toDate" disabled>
                                                            @else
                                                                <input type="date" value="{{ old('invoicedate') }}"
                                                                       name="todate" class="form-control pull-right"
                                                                       id="toDate">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 mt-4">
                                                    @if(!empty($inputDisabled) && $inputDisabled)
                                                        <button type="submit" class="btn btn-primary" id="submitBtn"
                                                                disabled>
                                                            Submit
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                                            Submit
                                                        </button>
                                                    @endif
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="col-md-12">
                                                        <label class="control-label">Type</label>
                                                        @if(!empty($inputDisabled) && $inputDisabled)
                                                            <select name="type" class="form-control pull-right"
                                                            id="typeId" disabled>
                                                                <option value="detailed">Detailed</option>
                                                                <option value="simple">Simple</option>
                                                            </select>
                                                        @else
                                                            <select name="type" class="form-control pull-right"
                                                            id="typeId">
                                                                <option value="detailed">Detailed</option>
                                                                <option value="simple">Simple</option>
                                                            </select>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="col-md-12" id="sectionId" style="display:none">
                                                        <label class="control-label">Sections</label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="employee_details" id="Employee details" name="sections[]" checked>
                                                            <label class="form-check-label" for="Employee details">
                                                                Employee details
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="leaves_summary" id="Employee details" name="sections[]" >
                                                            <label class="form-check-label" for="Leaves Summary">
                                                                Leaves Summary
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="report_period" id="Report period" name="sections[]">
                                                            <label class="form-check-label" for="Report period">
                                                                Report period
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="summary_section" id="Summary section" name="sections[]">
                                                            <label class="form-check-label" for="Summary section">
                                                                Summary section
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="work_summary" id="Work summary" name="sections[]">
                                                            <label class="form-check-label" for="Work summary">
                                                                Work summary
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="ip_summary" id="IP summary" name="sections[]">
                                                            <label class="form-check-label" for="IP summary">
                                                                IP summary
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Code</th>
                                                        <th>Name</th>
                                                        <th>Active</th>
                                                        <th>Department</th>
                                                        <th>JoiningDate</th>
                                                        <th>ResignDate</th>
                                                        <th>Check</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($employees as $employ)
                                                        <tr>
                                                            <td>{{$employ->emp_code}}</td>
                                                            <td>{{$employ->emp_name}}</td>
                                                            <td>{{$employ->status_active}}</td>
                                                            <td>{{$employ->dept_name}}</td>
                                                            <td>{{$employ->joindate}}</td>
                                                            <td>{{$employ->resigndate}}</td>
                                                            <td>
                                                                <input class="employees" type="checkbox" value="{{$employ->id}}">
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <input type="hidden" name="employee" id="employee-ids">
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @if(!empty($inputDisabled)  && $inputDisabled)
                    <h4 style="text-align: center; position: sticky; bottom: 1rem; background-color: lightgrey; display: none"
                        id="reportStatus">
                        Processing data for <label id="employeeIdName"></label>
                    </h4>
                    <input type="hidden" id="reportStatusUrl" value="{{route('reports.status', $reportId)}}">
                    <input type="hidden" id="reportDownloadUrl" value="{{route('reports.download', $reportId)}}">
                    <input type="hidden" id="reportIndexUrl" value="{{route('reports.index')}}">
                    <h4 id="downloadDiv" style="text-align: center; position: sticky; bottom: 1rem; display: none">
                        <button id="downloadBtn" class="btn btn-success btn-lg">DownloadReport</button>
                    </h4>
            @endif
            <!-- /.card -->

                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>

@endsection

@section('footer-scripts')
    <script>
        $(document).ready(function () {
            let interval = null;
            $.fn.getReportStatus = () => {
                $("#loader").addClass('loader');
                $.ajax({
                    type: "GET",
                    url: $('#reportStatusUrl').val(),
                    success: function (data) {
                        if (data.pdf_created == 1) {
                            clearInterval(interval);
                            $('#reportStatus').css('display', 'none');
                            $('#downloadDiv').css('display', 'block');
                            $("#loader").removeClass('loader');
                        } else {
                            if (data.status == 'failed') {
                                clearInterval(interval);
                                $('#reportStatus').html('Error in processing the data.');
                                $('#reportStatus').css('display', 'block');
                                $('#fromDate').removeAttr('disabled');
                                $('#toDate').removeAttr('disabled');
                                $('#submitBtn').removeAttr('disabled');
                            } else {
                                if (data.pdf_status == 'processing') {
                                    $('#reportStatus').html('Generating PDF report...').css('font-weight', 'bold');
                                    $('#reportStatus').css('display', 'block');
                                } else {
                                    if (data.emp_id && data.emp_name) {
                                        $('#employeeIdName').html(' ' + data.emp_id + '-' + data.emp_name);
                                        $('#reportStatus').css('display', 'block');
                                    }
                                }
                            }
                        }
                    },
                    error: function (data) {
                        $('#reportStatus').css('display', 'block');
                        clearInterval(interval);
                        $('#reportStatus').html('Error in processing the data.');
                        $('#fromDate').removeAttr('disabled');
                        $('#toDate').removeAttr('disabled');
                        $('#submitBtn').removeAttr('disabled');
                    }
                });
            };

            $.fn.checkReportStatus = () => {
                interval = setInterval($.fn.getReportStatus, 1000);
            }

            if ($('#reportStatus').length) {
                $.fn.checkReportStatus();
            }

            $('#downloadBtn').on('click', function () {
                let a = document.createElement('a');
                a.target = '_blank';
                a.href = $('#reportDownloadUrl').val();
                a.click();

                setTimeout(function () {
                    window.location.replace($('#reportIndexUrl').val());
                }, 1000);

            });

            $(document).on('change', '.employees', function (){
                let employees = $('#employee-ids').val();
                employees = employees.length ? employees.split(',') : [];
                let thisEmployeeId = $(this).val();
                if (this.checked) {
                    // Append
                    employees[employees.length] = thisEmployeeId;
                } else {
                    // Remove
                    employees.splice(employees.indexOf(thisEmployeeId), 1)
                }
                $('#employee-ids').val(employees.join());
            });


        });

        jQuery('body').on('change','#typeId',function(){
            $("#sectionId").hide();
            if($(this).val() == "simple"){
                $("#sectionId").show();
            }
        });
    </script>
@endsection

