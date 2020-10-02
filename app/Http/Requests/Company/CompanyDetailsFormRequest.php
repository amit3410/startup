<?php

namespace App\Http\Requests\Company;

use App\Http\Requests\Request;

class CompanyDetailsFormRequest extends Request
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
    //share_parent_id;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {  
        $rules=[];
       
            $rules['companyname']='required|min:2|max:50|regex:/^[a-zA-Z]/u|';
            $rules['customername']='required|min:2|max:50|regex:/^[a-zA-Z]/u|';
            $rules['regisno']='required|max:30|regex:/^[a-zA-Z0-9]/u|';
            $rules['regisdate']='required';
            $rules['status']='required';
            $rules['naturebusiness']='required';
            
        return $rules;
    }


    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
       
        return $messages = [
            'companyname.required' => trans('forms.corp_company_details.server_error.customername_required'),
            'companyname.min' => trans('forms.corp_company_details.server_error.customername_min'),
            'companyname.max' => trans('forms.corp_company_details.server_error.customername_max'),
            'companyname.regex' => trans('forms.corp_company_details.server_error.customername_regex'),
            'customername.required' => trans('forms.corp_company_details.server_error.customername_required'),
            'customername.min' => trans('forms.corp_company_details.server_error.customername_min'),
            'customername.max' => trans('forms.corp_company_details.server_error.customername_max'),
            'customername.regex' => trans('forms.corp_company_details.server_error.customername_regex'),
            'regisno.required' => trans('forms.corp_company_details.server_error.regisno_required'),
            'regisno.min' => trans('forms.corp_company_details.server_error.regisno_min'),
            'regisno.max' => trans('forms.corp_company_details.server_error.regisno_max'),
            'regisno.regex' => trans('forms.corp_company_details.server_error.regisno_regex'),
            'regisdate.required' => trans('forms.corp_company_details.server_error.regisdate_required'),
            'status.required' => trans('forms.corp_company_details.server_error.status_required'),
            'naturebusiness.required' => trans('forms.corp_company_details.server_error.naturebusiness_required'),
        ];
    }
}
