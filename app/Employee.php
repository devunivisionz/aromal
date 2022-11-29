<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $table = 'emp_records';
    protected $id = 'id';

    public static function getStatus($statusCode): string
    {
        $status = null;
        switch ($statusCode) {
            case "Y":
                $status = "Active";
                break;
            case "N":
                $status = "Relieved";
                break;
            case "D":
                $status = "Absconded";
                break;
            default:
                $status = "NA";
                break;
        };
        return $status;
    }

    public static function getData()
    {
        return static::leftJoin('emp_departments', 'emp_departments.id', 'emp_records.emp_department_id')
            ->select('emp_records.id', 'emp_departments.dept_name', 'emp_records.emp_code', 'emp_records.emp_name', 'emp_records.date_of_joining as joindate', 'emp_records.date_of_resignation as resigndate', 'emp_records.status_active')
            ->orderBy('emp_records.date_of_joining', 'DESC')
            ->get();
    }

}
