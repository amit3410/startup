<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return [

    // Indivisual Registration Form lang file
    'Individual_Reg' => [

        'Label' => [
            'title' => 'Sign up (Individual)',
            'country_id' => 'Nationality',
            'f_name' => 'First Name',
            'm_name' => 'Middle Name',
            'l_name' => 'Last Name',
            'date_of_birth' => 'Date of Birth',
            'email' => 'Official Email Address',
            'phone_no' => 'Official Mobile Number',
            'already_acc' => 'Already have an account?',
        ],

        'placeholder' => [
            'select' => 'Select',
        ],

        'client_error' => [
            'req_first_name' => 'First name is required.',
            'invalid_first_name' => 'Please enter valid first name.',
            'first_name_max_length' => 'You are not allow to enter maximum 50 characters.',
            'req_middle_name' => 'Middle name is required.',
            'invalid_middle_name' => 'Please enter valid middle Name.',
            'middle_name_max_length' => 'please enter no more than 50 characters.',
            'req_dob' => 'This field is required.',
            'invalid_age' => 'Sorry, you must be 18 years of age to apply',
            'req_dob_name' => 'This field is required.',
            'req_last_name' => 'This field is required.',
            'invalid_last_name' => 'Please enter valid last name.',
            'last_name_max_length' => 'You are not allow to enter maximum 50 characters.',
            'req_email' => 'This field is required.',
            'invalid_email' => 'Please enter a valid email address.',
            'email_max_length' => 'Please enter no more than 50 characters.',
            'email_already_exists' => 'The email is already registered.',
            'req_phone' => 'This field is required',
            'phone_minlength' => 'Please enter valid phone number.',
            'phone_maxlength' => 'You are not allow to enter maximum 10 digits.',
            'positive_phone_no' => 'Please enter positive mobile no',
            'invalid_phone' => 'Please enter valid phone number.',
            'req_country' => 'Please select country.',
            'invalid_country' => 'Please select a valid country.',
            'req_country_code' => 'Please select country code.',
        ],

        'server_error' => [
            'req_first_name' => 'This field is required.',
            'invalid_first_name' => 'Please enter valid first name.',
            'first_name_max_length' => 'You are not allow to enter maximum 50 characters.',
            'req_middle_name' => 'Middle name is required.',
            'invalid_middle_name' => 'Please enter valid middle Name.',
            'middle_name_max_length' => 'please enter no more than 50 characters.',
            'req_dob' => 'This field is required.',
            'invalid_age' => 'Sorry, you must be 18 years of age to apply',
            'req_dob_name' => 'This field is required.',
            'req_last_name' => 'This field is required.',
            'invalid_last_name' => 'Please enter valid last name.',
            'last_name_max_length' => 'You are not allow to enter maximum 50 characters.',
            'req_email' => 'This field is required.',
            'invalid_email' => 'Please enter a valid email address.',
            'email_max_length' => 'Please enter no more than 50 characters.',
            'email_already_exists' => 'The email is already registered.',
            'req_phone' => 'This field is required',
            'phone_minlength' => 'Please enter valid phone number.',
            'phone_maxlength' => 'You are not allow to enter maximum 10 digits.',
            'positive_phone_no' => 'Please enter positive mobile no',
            'invalid_phone' => 'Please enter valid phone number.',
            'req_country' => 'Please select country.',
            'invalid_country' => 'Please select a valid country.',
        ],
    ],

    // Corperate Registration Form lang file
    'Corperate_Reg' => [

        'Label' => [
            'title' => 'Sign up (Corporate)',
            'country_id' => 'Country of Registration',
            'corp_name' => 'Company Name',
            'corp_date_of_formation' => 'Company Date of Formation',
            'corp_license_number' => 'Company Trade License Number',
            'comp_owner' => 'Company Authorised Signatory',
            'f_name' => 'First Name',
            'm_name' => 'Middle Name',
            'l_name' => 'Last Name',
            'email' => 'Official Email Address',
            'phone_no' => 'Official Mobile Number',
            'already_acc' => 'Already have an account?',
            'date_of_bilrth' => 'Authorized Signatory Date of Birth',
        ],

        'placeholder' => [
            'select' => 'Select',
        ],

        'client_error' => [
            'invalid_name' =>'Please enter valid company name',
            'invalid_len'  =>'Please enter maximum 50 characters',
            'req_country_code' => 'Please select country code.',
            'maxlength'=> 'Please enter maximum 30 digits',
        ],

        'server_error' => [

        ],
    ],

    // Indivisual/Corperate Login Form lang file
    'IndCorp_Login' => [
        'Label' => [
            'title' => 'Sign in (Individual or Corporate)',
            'username' => 'Username',
            'password' => 'Password',
            'forgot_pass' => 'Forgot Password?',
            'ques' => "Don't have an account?",
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    // Otp Form lang file
    'Otp_Form' => [
        'Label' => [
            'heading' => 'OTP',
            'is_verified' => 'Verify OTP',
            'is_otp_resent' => 'Resend OTP',
            'thanks_email_verify' => 'Thank you for verifying your email ID!',
            'otp_no' => 'We have sent you an OTP on your registered mobile number. Please enter OTP Below',
        ],

        'plc_holder' => [
            'enter_otp' => 'Enter OTP',
        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    // Reset Password Form lang file
    'ChgPass_Form' => [
        'Label' => [
            'heading' => 'Change Password',
            'old_pass' => 'Old Password',
            'new_pass' => 'New Password',
            'confirm_pass' => 'Confirm New Password',
            'dashboard' => 'Dashboard',
        ],

        'plc_holder' => [
            'enter_conf_pass' => 'Confirm Your Password',
            'enter_new_pass' => 'Enter New Password',
            'enter_old_pass' => 'Enter Old Password',
        ],

        'client_error' => [

        ],

        'server_error' => [

       'cur-req' =>'Old Password is required.',
        'new-req' =>'New Password is required.',
        'req' =>'This field is required',
       'min' =>'New Password must contain atleast 8 characters.',
       'max' =>'New Password must contain atmost 15 characters.',
       'pass_rules' =>'Please Enter New Password with One Uppercase, One Number and One Special Character.',
       'confirm_pass' =>'Your new password and Confirm new password do not match.',
       'confirm_req'  =>'Confirm New Password is required.',
       'oldpass_msg'  =>'Your current password does not matches with the password you provided. Please try again.',
        'old_newpassmsg'  =>'New Password cannot be same as your current password. Please choose a different password.',


        ],
    ],

    //Indivisual dashbord Left Side Bar lang file
    'Ind_left_SideBar' => [
        'Label' => [
            'lbl_kyc' => 'KYC',
            'lbl_para' => 'Individual Natural Person (director, shareholder, Ultimate Beneficial Owner)',
            'item0' => 'DashBoard',
            'item1' => 'Change Password',
            'item2' => 'Personal Information',
            'item3' => 'Family Information',
            'item4' => 'Residential Information',
            'item5' => 'Documents',
            'item6' => 'Professional Information',
            'lbl_commer_info' => 'For Sole Proprietorship/ Self Employed',
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    // Company Details Form lang file
    'Company_Details' => [
        'Label' => [
            'title' => 'DexterCapital',
            'heading' => 'Sign up (Individual)',
            'country_id' => 'Nationality',
            'f_name' => 'First Name',
            'm_name' => 'Middle Name',
            'l_name' => 'Last Name',
            'date_of_birth' => 'Date of Birth',
            'email' => 'Official Email Address',
            'phone_no' => 'Official Mobile No',
            'ques' => 'Already have an account?',
        ],

        'plc_holder' => [
            'select_nationality' => 'Select Nationality',
            'select_dob' => 'Select Date of Birth',
            'enter_email' => 'Enter Email',
            'enter_mob_no' => 'Enter Mobile No',
            'enter_lname' => 'Enter Last Name',
            'enter _mname' => 'Enter Middle Name',
            'enter_fname' => 'Enter First Name',
        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    // Education Details Form lang file
    'Edu_Details' => [
        'Label' => [
            'heading' => 'Education Details',
            'uni_name' => 'University Name',
            'course_name' => 'Course Name',
            'date_att' => 'Dates Attended',
            'add_info' => 'Additional Information',
            'add_edu' => '+Add Education',
            'remark' => 'Remark',
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    // Otp Feedback lang file
    'otpThanks' => [
        'Label' => [
            'message' => 'Mobile Number Verification Successful!',
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Register Form lang file
    'Reg_Form' => [
        'Label' => [
            'heading' => 'Basic Details',
            'fname' => 'First Name',
            'lname' => 'Last Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'country' => 'Country',
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Research Form lang file
    'research_Form' => [
        'Label' => [
            'heading' => 'Research Publication',
            'title' => 'Title',
            'jor_mag' => 'Journal/Magazine',
            'my_pub' => 'Month/Year of Publication',
            'att_file' => 'Attach File ( one attachment at a time )',
            'add_research' => 'Add Research Publication',
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Skill Form lang file
    'skills_Form' => [
        'Label' => [
            'heading' => 'Skills',
            'add_skills' => 'Add Skills',
            'beginner' => 'Beginner',
            'intermediate' => 'Intermediate',
            'professional' => 'Professional',
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Thanks Blade lang file
    'thanks_Page' => [
        'Label' => [
            'message1' => 'Thank you for signing up with us!',
            'message2' => 'We have sent you an verification link on your email ID. Please verify your email ID to complete your registration.',
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Awards Form lang file
    'awards_Form' => [
        'Label' => [
            'heading' => 'Awards & Honors',
            'title' => 'Title',
            'brief_desc' => 'Brief Description',
            'add_awards' => 'Add Awards & Honors',
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Email Form lang file
    'email_Form' => [
        'Label' => [
            'heading' => 'Reset Password',
            'email' => 'E-Mail Address',
            'sent_pass_link' => 'Send Password Reset Link',
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Reset Password Form lang file
    'reset_Form' => [
        'Label' => [
            'heading' => 'Change password',
            'email' => 'E-Mail Address',
            'new_pass' => 'New Password',
            'conf_pass' => 'Confirm new Password',
            'reset_pass' => 'Reset Password',
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [
            'verify_email_msg'=>'The password reset link is no longer valid. Please request another password reset email from the login page.',
        ],
    ],

    //User Profile Form lang file
    'personal_Profile' => [
        'Label' => [
            'kyc' => 'KYC',
            'text' => 'Individual Natural Person (director, shareholder, Ultimate Beneficial Owner)',
            'item1' => 'Personal Information',
            'item2' => 'Professional Information',
            'item3' => 'Financial Information',
            'item4' => 'Documents',
            'heading' => 'Personal Information',
            'f_name' => 'First Name',
            'm_name' => 'Middle Name',
            'l_name' => 'Last Name',
            'gender' => 'Gender',
            'date_of_birth' => 'Date of Birth',
            'birth_country_name' => 'Country of Birth',
            'birth_country_id' => 'Nationality',
            'birth_state_id' => 'State of Birth',
            'birth_city_id' => 'City of Birth',
            'father_name' => "Father's Name",
            'mother_f_name' => "Mother's First Name",
            'mother_m_name' => "Mother's Maiden Name",
            'reg_no' => 'Registration Number',
            'reg_place' => 'Registration Place',
            'f_nationality_id' => 'Nationality',
            'sec_nationality_id' => 'Second Nationality',
            'document_type' => 'Document Type',
            'document_number' => 'Document Number',
            'issuance_date' => 'Issuance Date',
            'expire_date' => 'Expiry Date',
            'social_media' => 'Social Media',
            'social_media_link' => 'Social Media Link',
            'residence_status' => 'Residence Status',
            'family_status' => 'Family Status',
            'guardian_name' => 'Legal Guardians Name',
            'legal_maturity_date' => 'Legal Muturity Date',
            'educational_level' => 'Educational Level',
            'is_residency_card' => 'Do you have any residency card',
            'do_you_hold_held' => 'Do you hold or have ever held a Senior position in the public sector / Political position',
            'senior_position_sector' => 'Senior position in the public sector',
            'political_position' => 'Political position ',
            'are_you_related_directly_or_indirectly' => 'Are you related directly or indirectly to a person currently holding or who has previously held a Senior position in the public sector / Political position',
            'political_position_dec' => 'If yes, please specify position(s)',
            'message1' => 'Dear Applicant;',
            'message2' => 'Welcome to the Compliance platform of Dexter Capital Financial Consultancy LLC.',
            'message3' => 'According to the United Arab Emirates rules and regulations and the International applicable laws, you are kindly requested to proceed with the due diligence application allowing you to validate your profile and access many financial platforms.',
            'message4' => 'Dexter Capital Financial Consultancy LLC being regulated by Securities and Commodities Authority in the UAE, is committed to maintain all your information confidential and highly protected by the most sophisticated security tools and is in full compliance with the requirements of the European Union related to the General Data Protection Regulation (GDPR).',
            'message5' => 'https://ec.europa.eu/info/law/law-topic/data-protection/data-protection-eu_en',
            'add' => '+Add',
            'date_of_expiry' => 'Passport Expiry Date',
        ],

        'plc_holder' => [
            'enter_fname' => 'Enter First Name',
            'enter_mname' => 'Enter Middle Name',
            'enter_lname' => 'Enter Last Name',
            'enter_father_name' => "Enter Father's Name",
            'enter_mother_name' => "Enter Mother's Name",
            'enter_mother_mname' => "Enter Mother's Maiden Name",
            'enter_reg_no' => 'Enter Registration No',
            'enter_reg_place' => 'Enter Registration Place',
            'enter_social_mlink' => 'Enter Social Media Link',
            'enter_gardians_name' => "Enter Gardians Name",

            'select_gender' => 'Select Gender',
            'select_nationality' => 'Select Nationality',
            'select_state_birth' => 'Select State of Birth',
            'select_city_birth' => 'Select City of Birth',
            'select_second_nationality' => 'Select Second Nationality',
            'select_document_type' => 'Select Document Type',
            'select_social_media' => 'Select Social Media',
            'select_residence_status' => 'Select Residence Status',
            'select_family_status' => 'Select Family Status',
            'select_educational_level' => 'Select Eductional Level',
            'enter_other_edu'          =>'Enter Education' ,    
            'select_card_availability' => 'Select Residence Card',
            'select_title'   =>'Title',
            'identity_card_no'=>'Identity Card Number',
            'passport_no'=>'Passport Number',
            'civil_register'=>'Civil Register',
            'other_document'=>'Other Document',

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Family Information lang file
    'family_Info' => [
        'Label' => [
            'heading' => 'Family Information',
            'spouse_f_name' => 'Spouse First Name',
            'spouse_m_name' => 'Spouse Maiden Name',
            'is_spouse_profession' => 'Spouse Professional Status',
            'spouse_profession' => "Spouse's Profession (if only)",
            'spouse_employer' => "Spouse's Employer (if any)",
            'children_info' => 'Children Information',
            'is_child' => 'No Children',
            'spouce_child_info' => 'Child 1',
            'child_name' => 'Child Name',
            'child_dob' => 'Date of Birth',
        ],

        'plc_holder' => [
            'child_dob' => 'Select Date of Birth',
            'child_name' => 'Enter Child Name',
            'spouse_employer' => 'Enter Employer Name',
            'spouse_profession' => 'Enter Profession Name',
            'spouse_m_name' => 'Enter Middle Name',
            'spouse_f_name' => 'Enter First Name',
        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Professional Information lang file
    'profession_Info' => [
        'Label' => [
            'heading' => 'Professional Information',
            'prof_status' => 'Professional Status',
            'other_prof_status' => 'Other Profession Status',
            'prof_detail' => 'Profession/ Occupation in detail Previous Profession/ Occupation if retired',
            'position_title' => 'Position/ Job title Last Position/ Job title if retired',
            'date_employment' => 'Date of Employment/ Retirement',
            'last_monthly_salary' => 'Last Month Salary (if retired)',
        ],

        'plc_holder' => [
            'date_employment' => 'Select Date of Birth',
            'last_monthly_salary' => 'Enter here',
        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Financial Information lang file
    'financial_Info' => [
        'Label' => [
            'heading' => 'Financial Information',
            'source_funds' => 'Source of Funds',
            'other_source_fund' => 'Other Source Of Funds',
            'jurisdiction_funds' => 'Juridiction of Funds',
            'annual_income' => 'Annual Income (in USD)',
            'estimated_wealth' => 'Estimated Wealth (in USD)',
            'wealth_source' => 'Kindly provide details on the source(s) of your wealth',
            'other_wealth_source' => 'Other source(s) of your wealth',
            'message' => 'Please fill the following details (If Applicable)',
            'tin_code' => 'US TIN Code',
            'is_abandoned' => 'Was US citizenship abandoned after June 2014?',
            'date_of_abandonment' => 'Please specify date of abandonment',
            'abandonment_reason' => 'Reason',
            'justification' => 'Justification (If reason B is selected)',
            'tin_country_name' => 'Tin Country',
            'tin_number' => 'TIN (Taxpayer Identification Number) or functional equivalent of the TIN',
        ],

        'plc_holder' => [
            'other_source_fund' => 'Enter other Source Of Funds',
            'other_wealth_source' => 'Enter other source(s) of your wealth',
            'tin_code' => 'Enter US TIN Code',
            'date_of_abandonment' => 'Enter date of abandonment',
            'abandonment_reason' => 'Enter Reason',
            'justification' => 'Enter Reason',
            'tin_number' => 'Enter TIN No.',
        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Commercial Information lang file
    'commercial_Info' => [
        'Label' => [
            'heading1' => 'For Sole Proprietorship/ Self Employed, Please Specify',
            'comm_name' => 'Commercial Name',
            'date_of_establish' => 'Date of Establishment',
            'country_establish_id' => 'Country of Establishment',
            'comm_reg_no' => 'Commercial Regitration No.',
            'comm_reg_place' => 'Commercial Register Place',
            'comm_country_id' => 'Commercial Register Country',
            'country_activity' => 'Country(ies) of Activity',
            'syndicate_no' => 'Syndicate No.',
            'taxation_no' => 'Taxation ID No.',
            'taxation_id' => 'Taxation ID',
            'annual_turnover' => 'Annual Business Turnover (in $)',
            'main_suppliers' => 'Main Suppliers',
            'main_clients' => 'Main Clients',
            'authorized_signatory' => 'Name of Authorized Signatory',

            'heading2' => 'Business Address',
            'buss_country_id' => 'Country',
            'buss_city_id' => 'City',
            'buss_region' => 'Region',
            'buss_building' => 'Building',
            'buss_floor' => 'Floor',
            'buss_street' => 'Street',
            'buss_postal_code' => 'Postal Code',
            'buss_po_box_no' => 'P.O Box',
            'buss_email' => 'Email',
            'buss_telephone_no' => 'Telephone No.',
            'buss_mobile_no' => 'Mobile No.',
            'buss_fax_no' => 'Fax No.',

            'heading3' => 'Mailing Address',
            'is_hold_mail' => 'Host Mail',
            'mailing_address' => 'In case of sending documents through mail, please specify mailing address',
            'relation_exchange_company' => 'Relation with Exchange Company/ Establishment',
            'concerned_party' => 'Name of Concerned Party',
            'details_of_company' => 'Name/Details of Establishment/Company',
        ],

        'plc_holder' => [
            'comm_name' => 'Enter Commercial Name',
            'date_of_establish' => 'Select Date',
            'country_establish_id' => 'Enter Commercial Register No.',
            'comm_reg_place'=>'Commercial Register Place',
            'syndicate_no' => 'Enter Syndicate No.',
            'taxation_no' => 'Enter Taxation ID No.',
            'taxation_id' => 'Enter Taxation ID',
            'annual_turnover' => 'Enter Annual Business Turnover (in $)',
            'main_suppliers' => 'Enter Main Suppliers',
            'main_clients' => 'Enter Main Clients',
            'authorized_signatory' => 'Enter Name of authorized signatory',
            'buss_city_id' => 'Enter City',
            'buss_region' => 'Enter Region',
            'buss_building' => 'Enter Building',
            'buss_floor' => 'Enter Floor',
            'buss_street' => 'Enter Street',
            'buss_postal_code' => 'Enter Postal Code',
            'buss_po_box_no' => 'Enter P.O. Box no.',
            'buss_email' => 'Enter Email Address',
            'buss_telephone_no' => 'Enter Telephone Number',
            'buss_mobile_no' => 'Enter Mobile Number',
            'buss_fax_no' => 'Enter Fax Number',
            'concerned_party' => 'Enter Name of Concerned Party',
            'details_of_company' => 'Enter Name/Details of Establishment/Company',
        ],

        'client_error' => [
            'min_mob_no'=>'Please enter at least 8 characters.',
            
        ],

        'server_error' => [

        ],
    ],

    //Residensial Information lang file
    'residensial_Info' => [
        'Label' => [
            'heading' => 'Residential Information',
            'country_id' => 'Country',
            'city_id' => 'City',
            'region' => 'Region',
            'building_no' => 'Building',
            'floor_no' => 'Floor/Apartment No.',
            'street_addr' => 'Street',
            'postal_code' => 'Postal Code',
            'post_box' => 'P.O Box',
            'addr_email' => 'Email',
            'addr_telephone' => 'Telephone Number',
            'addr_mobile_no' => 'Mobile Number',
            'addr_fax_no' => 'Fax Number',

        ],

        'plc_holder' => [
            'city_id' => 'Enter City',
            'region' => 'Enter Region',
            'building_no' => 'Enter Building',
            'floor_no' => 'Enter Floor',
            'street_addr' => 'Enter Street',
            'postal_code' => 'Enter Postal Code',
            'post_box' => 'Enter P.O. Box no.',
            'addr_email' => 'Enter Email',
            'addr_phone_no' => 'Enter Telephone Number',
            'addr_mobile_no' => 'Enter Mobile Number',
            'addr_fax_no' => 'Enter Fax Number',
        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Upload Documents lang file
    'upload_Docs' => [
        'Label' => [
            'heading' => 'Documents & Declaration',
            'message1' => 'Documents to be provided along with this form',
            'browse' => 'Browse',
            'declararion' => 'Declaration',
            'download' => 'Download',
            'message2' => 'We hereby declare that the particulars given herein are true, correct',
            'message3' => 'complete to the best of our knowledge',
            'message4' => 'belief. We undertake to promptly inform Dexter Capital Financial Consultancy LLC of any changes or information provided hereinabove.',
            'message5' => 'I accept the Terms and Conditions',
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
        'success' =>[
            
            'thanks_msg' =>'you successfully completed and
                            submitted the KYC form with Dexter Capital Financial Consultancy
                            LLC. The Compliance Department will review it in two working days,
                            and you will receive the final decision on your registered Email
                            address. In case additional documents or information required the
                            Compliance,department will notify you. You can check the status of
                            the profile in your personal registered account.
                            By submitting the KYC form, you as the Customer undertakes that
                            the information provided and submitted on the KYC is true and
                            nothing has been forged or hidden. The Compliance Platform takes
                            no responsibility for any charge or liability arising out of the
                            information provided by you.',
        ],
    ],
    //Corperate Address Details form lang file
    'corp_address_details' => [
        'Label' => [
            'lbl_kyc' => 'KYC',
            'lbl_para' => 'Individual Natural Person (director, shareholder, Ultimate Beneficial Owner)',
            'item1' => 'Company Details',
            'item2' => 'Address Details',
            'item3' => 'Shareholding Structure',
            'item4' => 'Financial Information',
            'item5' => 'Documents & Declaration',
            'heading' => 'Address Details',
            'permanent_add' => 'Permanent/Registered Address:',
            'country_id' => 'Country',
            'city_id' => 'City',
            'region' => 'Region',
            'building' => 'Building',
            'floor_no' => 'Floor/Office',
            'street' => 'Street',
            'postal_code' => 'Postal Code',
            'po_box' => 'P.O Box',
            'email' => 'Email Address',
            'area_code' =>'Area Code',
            'telephone' => 'Telephone Number',
            'country_code' =>'Country Code',
            'mobile' => 'Mobile Number',
            'addr_fax_no' => 'Fax Number',
            'corres_add' => 'Address for Correspondence',
            'same_add' => 'Same as Registered Address',
            'corre_country' => 'Country',
            'corre_city' => 'City',
            'corre_region' => 'Region',
            'corre_building' => 'Building',
            'corre_floor' => 'Floor/Office',
            'corre_street' => 'Street',
            'corre_postal_code' => 'Postal Code',
            'corre_po_box' => 'P.O Box',
            'corre_email' => 'Email Address',
            'corre_telephone' => 'Telephone Number',
            'corre_mobile' => 'Mobile Number',
            'corre_fax' => 'Fax Number',
        ],

        'plc_holder' => [
            'country_id' => 'Country',
            'city_id' => 'Enter City',
            'region' => 'Enter Region',
            'building_no' => 'Enter Building',
            'floor_no' => 'Enter Floor',
            'street_addr' => 'Enter Street',
            'postal_code' => 'Enter Postal Code',
            'post_box' => 'Enter P.O. Box No.',
            'addr_email' => 'Enter Email',
            'addr_phone_no' => 'Enter Telephone No.',
            'addr_mobile_no' => 'Enter Mobile No.',
            'addr_fax_no' => 'Enter Fax No.',
        ],

        'client_error' => [
            'country_required' => "This field is required",

            'city_required' => "This field is required",
            'city_pattern' => "Please enter valid city name",
            'city_maxlength' => "Please enter maximum 50 characters",

            'region_required' => "This field is required",
            'region_pattern' => "Please enter alphabetical only characters",
            'region_maxlength' => "Please enter maximum 30 characters",

            'building_required' => "This field is required",
            'building_pattern' => "Please enter only characters",
            //  building_minlength => "Please enter at least 2 characters",
            'building_maxlength' => "Please enter maximum 50 characters",

            'street_required' => "This field is required",
            'street_pattern' => "Please enter only alphabetical characters",
            'street_maxlength' => "Please enter maximum 20 characters",

            'postalcode_required' => "This field is required",
            'postalcode_alphanumeric' => "Please enter only alphabetical characters",
            'postalcode_maxlength' => "Please enter maximum 10 characters",

            'pobox_required' => "This field is required",

            'email_required' => "This field is required",
            'email_pattern' => "Please enter a valid email address",

            'telephone_required' => "This field is required",
            'telephone_maxlength' => "Please enter maximum 10 number",
            'telephone_digits' => "Please enter valid telephone no",

            'mobile_required' => "This field is required",
            'mobile_maxlength' => "Please enter maximum 10 number",
            'mobile_digits' => "Please enter valid mobile no",

            'faxno_required' => "This field is required",

            'corr_country_required' => "This field is required",

            'corr_city_required' => "This field is required",

            'corr_region_required' => "This field is required",

            'corr_building_required' => "This field is required",
            'corr_building_pattern' => "Please enter only characters",
            'corr_building_maxlength' => "Please enter maximum 50 characters",

            'corr_street_required' => "This field is required",
            'corr_street_pattern' => "Please enter only alphabetical characters",
            'corr_street_maxlength' => "Please enter maximum 20 characters",

            'corr_postal_required' => "This field is required",
            'corr_postal_alphanumeric' => "Please enter only alphabetical characters",
            'corr_postal_maxlength' => "Please enter maximum 10 characters",

            'corr_pobox_required' => "This field is required",

            'corr_email_required' => "This field is required",
            'corr_email_pattern' => "Please enter a valid email address",

            'corr_tele_required' => "This field is required",
            'corr_tele_maxlength' => "Please enter maximum 10 number",
            'corr_tele_digits' => "Please enter valid telephone no",

            'corr_mobile_required' => "This field is required",
            'corr_mobile_maxlength' => "Please enter maximum 10 number",
            'corr_mobile_digits' => "Please enter valid mobile no",

            'corr_fax_required' => "This field is required",

        ],

        'server_error' => [

        ],
    ],

    //Corperate Beneficiary Owner Blade lang file
    'corp_beneficiary_owner' => [
        'Label' => [
            'page_title' => 'Shareholding Structure',
            'heading' => 'Ultimate Beneficiary Owners',
            'th_name' => 'Name',
            'th_share' => 'Shareholding %',
            'th_action' => 'Actions',
            'th_status' => 'Status',
            'pending' => 'Pending',
            'sym' => '%',
            'lbl_kyc' => 'KYC',
            'is_kyc' => 'KYC Not Required',
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Corperate Company Details Form lang file
    'corp_company_details' => [
        'Label' => [
            'name_of_company'=>'',
            'heading' => 'Company Details',
            'company_name'=>'Name of the Company',
            'corp_name' => 'Name of the Customer: (as per Certificate of Incorporation/ Registration)',
            'corp_date_of_formation' => 'Registration Date',
            'corp_license_number' => 'Registration Number',
            'status' => 'Status',
            'buss_nature' => 'Nature of Business',
            'message1' => 'Dear Applicant;',
            'message2' => 'Welcome to the Compliance platform of Dexter Capital Financial Consultancy LLC.',
            'message3' => 'According to the United Arab Emirates rules and regulations and the International applicable laws, you are kindly requested to proceed with the due diligence application allowing you to validate your profile and access many financial platforms.',
            'message4' => 'Dexter Capital Financial Consultancy LLC being regulated by Securities and Commodities Authority in the UAE, is committed to maintain all your information confidential and highly protected by the most sophisticated security tools and is in full compliance with the requirements of the European Union related to the General Data Protection Regulation (GDPR).',
            'message5' => 'https://ec.europa.eu/info/law/law-topic/data-protection/data-protection-eu_en',
        ],

        'plc_holder' => [
            'select_status' => 'Select Status',
            'corp_name' => 'Enter name of the Customer',
            'corp_date_of_formation' => 'Select Registration Date',
            'corp_license_number' => 'Registration Number',
            'enter_status' =>'Enter Status',
            'comp_name'=>'Enter Company Name',
        ],

        'client_error' => [
            'required' => 'This field is required',
            'pattern' => 'Please enter only alphabetical characters',
            'customername_minlength' => 'Please enter at least 2 alphabetical characters',
            'customername_maxlength' => 'Please enter maximum 50 characters',

            'regisno_required' => 'This field is required',
            'regisno_alphanumeric' => 'Please enter only number',
            'regisno_maxlength' => 'Please enter maximum 30 characters',

        ],

        'server_error' => [
            'customername_required' => 'This field is required',
            'customername_min' => 'Please enter at least 2 characters',
            'customername_max' => 'Please enter maximum 50 characters',
            'customername_regex' => 'Please enter only characters',

            'regisno_required' => 'This field is required.',
            'regisno_max' => 'Please enter maximum 30 characters',
            'regisno_regex' => 'Please enter only digits',
            'regisdate_required' => 'This field is required.',
            'status_required' => 'This field is required.',
            'naturebusiness_required' => 'This field is required.',
            
        ],
    ],

    //Corperates' Documents form lang file
    'corp_documents' => [
        'Label' => [
            'lbl_kyc' => 'KYC',
            'lbl_para' => 'Individual Natural Person (director, shareholder, Ultimate Beneficial Owner)',
            'item1' => 'Company Details',
            'item2' => 'Address Details',
            'item3' => 'Shareholding Structure',
            'item4' => 'Financial Information',
            'item5' => 'Documents & Declaration',
            'heading' => 'Documents & Declaration',
            'provided_docs' => 'Documents to be provided along with this form',
            'lbl_download' => 'Download',
            'lbl_reg' => 'Register',
            'lbl_browse' => 'Browse',
            'declaration' => 'Declaration',
            'message1' => 'We hereby declare that the particulars given herein are true, correct',
            'message2' => 'complete to the best of our knowledge',
            'message3' => 'belief. We undertake to promptly inform Dexter Capital Financial Consultancy LLC of any changes or information provided hereinabove.',
            'message4' => 'I accept the Terms and Conditions',
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],

    ],

    //Corperate Financial lang file
    'corp_financial' => [
        'Label' => [
            'lbl_kyc' => 'KYC',
            'lbl_para' => 'Individual Natural Person (director, shareholder, Ultimate Beneficial Owner)',
            'item1' => 'Company Details',
            'item2' => 'Address Details',
            'item3' => 'Shareholding Structure',
            'item4' => 'Financial Information',
            'item5' => 'Documents & Declaration',
            'heading' => 'Financial Information',
            'yearly_usd' => 'Yearly turnover in USD',
            'yearly_profit_usd' => 'Yearly profit in USD',
            'total_debts_usd' => 'Total debts in USD',
            'total_receivables_usd' => 'Total receivables in USD',
            'total_cash' => 'Total cash in banks in USD',
            'sym' => '$',
            'total_payables_usd' =>'Total payables in USD',
            'total_salary_usd'   =>'Total salaries in USD',
            'capital_company_usd'=>'Capital of the company in USD',
        ],

        'plc_holder' => [
            'enter_value' => 'Enter Value',
        ],

        'client_error' => [
            'required' =>'This field is required',
            'max_len' =>'Please enter maximum 20 digits',
        ],

        'server_error' => [

        ],
    ],

    //Corperate dashbord Left Side Bar lang file
    'Corp_left_SideBar' => [
        'Label' => [
            'lbl_kyc' => 'KYC',
            'lbl_para' => 'Individual Natural Person (Director, Shareholder, Ultimate Beneficiary Owner)',
            'item1' => 'Company Details',
            'item2' => 'Address Details',
            'item3' => 'Shareholding Structure',
            'item4' => 'Financial Information',
            'item5' => 'Documents & Declaration',
            'item6' => 'Others Documents',
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Corperate Shareholding Beneficiary lang file
    'shareholding_beneficiary' => [
        'Label' => [
            'heading' => 'Ultimate Beneficiary Owners',
            'th_name' => 'Name',
            'th_sharehol' => 'Shareholding %',
            'th_actions' => 'Actions',
            'th_status' => 'Status',
            'lbl_kyc' => 'KYC',
            'lbl_not_kyc' => 'KYC Not Required',
            'lbl_pending' => 'Pending',
            'sym' => '%',
            'page_tittle' => 'Shareholding Structure',
            'th_profile_score' => 'Profile Score',
        ],

        'plc_holder' => [

        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Corperate Shareholding Structure lang file
    'sharehoding_str' => [
        'Label' => [
            'heading' => 'Shareholding Structure',
            'lbl_ind_corp' => 'Individual/Corporate',
            'company_name' => 'Individual Name /Company Name',
            'passport_no' => 'Passport No. /License No.',
            'share_value' => 'Value in USD',
            'share_percentage' => 'Shareholding Percentage',
            'actual_share_percent' => '',
            'share_type' => 'Individual/Company',
            'add_sharehonder' => '+Add Shareholder',
        ],

        'plc_holder' => [
            'enter_value' => 'Enter value',
            'select_type' => 'Select Individual/company',
            'enter_percent' => 'Enter Percentage',
            'enter_name' => 'Enter Name',
            'enter_passport_no' => 'Enter Passport No./ License No.',
        ],

        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    //Corperate Uploaded Documents lan file
    'corperate_uploaddoc' => [
        'Label' => [
            'lbl_kyc' => 'KYC',
            'lbl_para' => 'Indivisual Natural Person (director, shareholder, Ultimate Beneficiary Owner)',
            'item1' => 'Company Details',
            'item2' => 'Address Details',
            'item3' => 'Shareholding Structure',
            'item4' => 'Financial Information',
            'item5' => 'Documents & Declaration',
            'heading' => 'Documents & Declaration',
            'provided_docs' => 'Documnets to be provided along with this form',
            'lbl_browse' => 'Browse',
            'lbl_dec' => 'Declaration',
            'lbl_para1' => 'We hereby declare that the particulars given herein are true, correct',
            'lbl_para2' => 'complete to the best of our knowledge',
            'lbl_para3' => 'belief. We undertake to promtly inform Dexter Capital Financial Consultancy LLC of any changes or information provided herein above.',
            'lbl_terms_cond' => 'I accept the Terms and Cionditions',
        ],

        'plc_holder' => [

        ],

        'client_error' => [
            'required'  =>'This field is required',
            'error'  =>'Please select at least one Document.',
            'server_error'=>'Some error occur.'
        ],

        'server_error' => [

        ],
        'success' =>[
            'msg_success' =>'Documents uploaded successfully !',
            'thanks_msg' =>'you successfully
                        completed and submitted the KYC form 
                        with Dexter Capital Financial Consultancy LLC. The Compliance
                        Department will review it in two working days, and you will receive
                        the final decision on your registered Email address. In case
                        additional documents or information required the Compliance,
                        department will notify you. You can check the status of the profile in
                        your personal registered account.
                        By submitting the KYC form the Company undertakes that the
                        information provided and submitted on the KYC is true and nothing
                        has been forged or hidden. The Compliance Platform takes no
                        responsibility for any charge or liability arising out of the information
                        provided by the Company.',
        ],
    ],

    //Dashboard
    'DashboradIndividual' => [
        'heading' => 'Dashboard',
    ],

   
    // Indivisual/Corperate Login Form lang file
    'UpdateDocument' => [
        
        'Label' => [
            'heading' => 'Update Document',
            'passport_no' => 'Passport No',
            'issue_date' => 'Issue Date',
            'expiry_date' => 'Expiry Date',
            'upload' => 'Upload',
        ],


        'client_error' => [

        ],

        'server_error' => [

        ],
    ],
    
    // Indivisual/Corperate Login Form lang file
    'OtherDocument' => [
        
        'Label' => [
            'heading' => 'Other Document',
            'document_name' => 'Document Name',
            'document_for' => 'Document For',
            'document_type' => 'Document Type',
            'document_required' => 'Document Required',
            'submit' => 'Submit',
            'add' => 'Add',
            'edit' => 'Edit',
        ],


        'client_error' => [

        ],

        'server_error' => [

        ],
    ],

    'CorpComplianceReport'=>[

        'server_error' =>[
            'only_digits' => 'Please Enter Only Number',
            'maxlen' => 'Please Enter Maximum 30 Characters',
        ],
    ],

];
