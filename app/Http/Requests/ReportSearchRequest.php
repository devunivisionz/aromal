<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fromdate' => 'required|date',
            'todate' => 'required|date|after:fromdate',
            'employee' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'fromdate.required' => 'From Date is required.',
            'todate.required' => 'To Date is required.',
            'employee.required' => 'Please select employee(s).',
        ];
    }
}
