<?php

namespace App\Http\Requests\Company;

use App\Http\Requests\Request;

class CompanyAddressFormRequest extends Request
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
       
            $rules['city']='required|max:30|regex:/^[a-z A-Z]/u|';
            $rules['region']='required|max:30|regex:/^[a-z A-Z]/u|';
            $rules['building']='required|regex:/^[a-zA-Z0-9]/u|';
            $rules['floor']='max:50|regex:/^[a-zA-Z0-9]/u|';
            $rules['street']='required|max:20|regex:/^[a-zA-Z0-9]/u|';
            $rules['postalcode']='max:10';
            $rules['email']='required|email';
            $rules['telephone']='required|min:8|max:20|regex:/^[0-9]/u|';
            $rules['mobile']='required|min:8|max:20|regex:/^[0-9]/u|';
            
            $rules['corr_city'] = 'required';
            $rules['corr_region'] = 'required|max:30|regex:/^[a-z A-Z]/u|';
            $rules['corr_building'] = 'required|max:50|regex:/^[a-zA-Z0-9]/u|';
            $rules['corr_street'] ='required|max:20|regex:/^[a-zA-Z0-9]/u|';
            $rules['corr_postal'] = 'max:10';
            $rules['corr_email'] = 'required|email';
            $rules['corr_tele'] = 'required|min:8|max:20|regex:/^[0-9]/u|';
            $rules['corr_mobile'] = 'required|min:8|max:20|regex:/^[0-9]/u|';
            
            
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
            'country_id.required' => 'This field is required',
            'city.required' => 'This field is required',
            'city.regex' => 'Please enter only alphabetical characters',
            'region.required' => 'This field is required',
            'region.regex' => 'Please enter only alphabetical characters',
            'region.max' => 'Please enter maximum 30 characters',
            'building.required' => 'This field is required',
            'building.regex' => 'Please enter only alphabetical characters',
            'building.max' => 'Please enter maximum 50 characters',
            'floor.regex' => 'Please enter only alphabetical characters',
            'floor.max' => 'Please enter maximum 50 characters',
            'street.required' => 'This field is required',
            'street.regex' => 'Please enter only alphabetical characters',
            'street.max' => 'Please enter maximum 20 characters',
            'postalcode.max' => 'Please enter maximum 10 characters',
            'postalcode.regex' => 'Please enter only alphabetical characters',
            // 'pobox.required'    => 'This field is required',
            'email.required' => 'This field is required',
            'email.email' => 'Please enter a valid email address',
            'telephone.required' => 'This field is required',
            'telephone.min' => 'Please enter minimum 8 number',
            'telephone.max' => 'Please enter maximum 20 number',
            'telephone.regex' => 'Please enter valid telephone no',
            'mobile.required' => 'This field is required',
            'mobile.min' => 'Please enter minimum 8 number',
            'mobile.max' => 'Please enter maximum 20 number',
            'mobile.regex' => 'Please enter valid mobile no',
            'faxno.required' => 'This field is required',
            //Address for Correspondence
            'corr_country.required' => 'This field is required',
            'corr_city.required' => 'This field is required',
            'corr_region.required' => 'This field is required',
            'corr_region.regex' => 'Please enter only alphabetical characters',
            'corr_region.max' => 'Please enter no more than 30 characters',
            'corr_building.required' => 'This field is required',
            'corr_building.regex' => 'Please enter only alphabetical characters',
            'corr_building.max' => 'Please enter no more than 50 characters',
            'corr_street.required' => 'This field is required',
            'corr_street.regex' => 'Please enter only alphabetical characters',
            'corr_street.max' => 'Please enter no more than 20 characters',
            'corr_postal.required' => 'This field is required',
            'corr_postal.max' => 'Please enter no more than 10 characters',
            'corr_postal.regex' => 'Please enter only alphabetical characters',
            'corr_pobox.required' => 'This field is required',
            'corr_email.required' => 'This field is required',
            'corr_email.email' => 'Please enter a valid email address',
            'corr_tele.required' => 'This field is required',
            'corr_tele.min' => 'Please enter minimum 8 number',
            'corr_tele.max' => 'Please enter maximum 20 number',
            'corr_tele.regex' => 'Please enter valid telephone no',
            'corr_mobile.required' => 'This field is required',
            'corr_mobile.min' => 'Please enter minimum 8 number',
            'corr_mobile.max' => 'Please enter maximum 20 number',
            'corr_mobile.regex' => 'Please enter valid mobile no',
            'corr_fax.required' => 'This field is required',
        ];
    }
}
