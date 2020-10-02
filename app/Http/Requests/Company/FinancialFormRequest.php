<?php

namespace App\Http\Requests\Company;

use App\Http\Requests\Request;

class FinancialFormRequest extends Request
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
       
            $rules['yearly_usd'] = 'required|max:20|regex:/^[0-9]/u|';
            $rules['total_debts_usd'] = 'required|max:20|regex:/^[0-9]/u|';
            $rules['total_recei_usd'] = 'required|max:20|regex:/^[0-9]/u|';
            $rules['total_cash_usd'] = 'required|max:20|regex:/^[0-9]/u|';
            $rules['total_payable_usd'] = 'required|max:20|regex:/^[0-9]/u|';
            $rules['total_payable_usd'] = 'required|max:20|regex:/^[0-9]/u|';
            $rules['total_salary_usd'] = 'required|max:20|regex:/^[0-9]/u|';
            $rules['yearly_profit_usd'] = 'required|max:20|regex:/^[0-9]/u|';
            $rules['capital_company_usd'] = 'required|max:20|regex:/^[0-9]/u|';
            
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
            'yearly_usd.required' => 'This field is required',
            'yearly_usd.regex' => 'Please enter only number float',
            'yearly_usd.max' => 'Please enter maximum 20 digits',
            'yearly_profit_usd.required' => 'This field is required',
            'yearly_profit_usd.regex' => 'Please enter only number float',
            'yearly_profit_usd.max' => 'Please enter maximum 20 digits',
            'total_debts_usd.required' => 'This field is required',
            'total_debts_usd.regex' => 'Please enter only number float',
            'total_debts_usd.max' => 'Please enter maximum 20 digits',
            'total_recei_usd.required' => 'This field is required',
            'total_recei_usd.regex' => 'Please enter only number float',
            'total_recei_usd.max' => 'Please enter maximum 20 digits',
            'total_cash_usd.required' => 'This field is required',
            'total_cash_usd.regex' => 'Please enter only number float',
            'total_cash_usd.max' => 'Please enter maximum 20 digits',
            'total_payable_usd.required' => 'This field is required',
            'total_payable_usd.regex' => 'Please enter only number float',
            'total_payable_usd.max' => 'Please enter maximum 20 digits',
            'total_salary_usd.required' => 'This field is required',
            'total_salary_usd.regex' => 'Please enter only number float',
            'total_salary_usd.max' => 'Please enter maximum 20 digits',
            'yearly_profit_usd.required' => 'This field is required',
            'yearly_profit_usd.regex' => 'Please enter only number float',
            'yearly_profit_usd.max' => 'Please enter maximum 20 digits',
            'capital_company_usd.required' => 'This field is required',
            'capital_company_usd.regex' => 'Please enter only number float',
            'capital_company_usd.max' => 'Please enter maximum 20 digits',
        ];
    }
}
