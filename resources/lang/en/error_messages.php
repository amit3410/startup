<?php
return [
    //registration error messages
    'req_first_name' => 'This field is required.',
    'invalid_first_name' => 'Please enter valid first name.',
    'first_name_max_length' => 'You are not allow to enter maximum 50 characters.',
    'req_middle_name' => 'Middle name is required.',

    'invalid_middle_name' => 'Please enter valid middle Name.',
    'middle_name_max_length' => 'please enter no more than 50 characters.',
    'req_dob' =>'This field is required.',
    'invalid_age' => 'Sorry, you must be 18 years of age to apply',
    'req_dob_name' => 'This field is required.',
    'req_otp' => 'OTP is required',
    'invalid_otp' => 'Numbers only',



    'req_last_name' => 'This field is required.',
    'invalid_last_name' => 'Please enter valid last name.',
    'last_name_max_length' => 'You are not allow to enter maximum 50 characters.',
    'req_email' => 'This field is required.',
    'req_user_name' => 'Username is required',
    'req_password' => 'This field is required',
    'invalid_email' => 'Please enter a valid email address.',
    'email_max_length' => 'Please enter no more than 50 characters.',
    'email_already_exists' => 'The email is already registered.',

    'req_dob' => 'This field is required.',
    'req_phone' => 'This field is required',
    'phone_minlength' => 'Please enter minimum 8 digits',
    'phone_maxlength' => 'Please enter maximum 20 digits.',
    'positive_phone_no' => 'Please enter valid mobile number',
    
    'invalid_phone' => 'Please enter valid number.',
    'req_country' => 'Please select country.',
    'invalid_country' => 'Please select a valid country.',
    'zip_code_min_length' => 'Please enter 5 digits - no hyphens.',
    'zip_code_max_length' => 'You are not allow to enter maximum 6 digits.',
    'zip_code_numeric' => 'Please enter valid zip code.',
    'file_size_error' => 'File size should be 10MB or below.',
    'user_id_not_found' => 'Session or user Id expire please refresh and try again.',
    'req_course_name' => 'Course name is required.',
    
    'req_right_title' =>'This field is required.',
    'req_right_type' =>'Please select type of rights.',
    'req_right_type_numeric' =>'Please select valid type of rights.',
    'req_right_number' =>'Rights number is required.',
    'req_right_inventor' =>'Please enter Inventor/Innovator.',
    'req_right_assignee' =>'Please enter Assignee/Owner.',    
    'req_right_cluster' =>'Please choose cluster.',
    'req_right_cluster_numeric' =>'Please choose valid cluster.',
    'req_right_date' =>'Please choose Date of Rights.',
    'req_right_description' =>'Please enter Description.',
    //Admin Error Messages
    'admin' => [
        'req_old_password' => 'Old password is required.',
        'req_new_password' => 'New password is required.',
        'req_confirm_password' => 'Confirm password is required.',
        'minlen_password' => 'Password should be minimum 6 characters.',
        'same_confirm_password' => 'New password and confirm password should be same.',
        'password_changed' => 'Password changed successfully.',
        'correct_old_password' => 'Please enter correct old password',
        'minlen_confirm_password' => 'Confirm password should be minimum 6 characters.',
    ],

    //frontend restet password
    'reset' =>[
        'minlen_password' => 'New Password must contain atleast 8 characters.',
        'maxlen_password' => 'New Password must contain atmost 15 characters.',
        'password_rules' =>'Please Enter New Password with One Uppercase, One Number and One Special Character.', 
        'conf_newpassword'=>'Your new password and Confirm new password do not match.',
    ],

    //Common error messages
    'data_not_found' => 'No record found',
    'no_data_found' => 'No record found',
    'send_array' => 'Please send array',
    'invalid_data_type' => 'Invalid data type',
    'invalid_token' => 'Token is invalid',
    'invalid_otp' => 'Invalid OTP',
    'email_already_verified' => 'Your email already verified please login to continue.',
    'generic.failure'=>'We have encountered a technical issue due to which operation is not completed. Please try after sometime.',
    'creadential_not_valid'=>'Invalid username or password',
    'account_blocked'=>'Your account is blocked. Please contact support@inventrust.com',
    'BlockChain_blocked'=>'Technical team is working on this issue.',
    
    /*************family information**********/
    'req_spouse_first_name'=>'Spouse first name is required.',
    'invalid_spouse_first_name' => 'Please enter valid Spouse first  Name.',
    'spouse_first_name_max_length' => 'You are not allow to enter maximum 100 characters.',
    
    'req_spouse_maiden_name' => 'Spouse maiden name is required.',
    'invalid_spouse_maiden_name' => 'Please enter valid Spouse maiden  Name.',
    'spouse_maiden_name_max_length' => 'You are not allow to enter maximum 100 characters.',
    
    'req_spouse_professional_status'=>'Spouse professional status is required.',
    'req_this_field'=>'This field is required',
    'contain_this_alpha_num'=>'This may only contain letters and numbers.',
    'somthing_wrong'=>'Somthing went wrong !!',

];
