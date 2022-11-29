<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeReportStatus extends Model
{
    protected $fillable = ['report_id', 'emp_id', 'emp_name', 'status', 'pdf_file_name', 'pdf_status'];
}
