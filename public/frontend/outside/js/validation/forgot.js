/* global messages, message */

try {
    jQuery(document).ready(function ($) {
        /*validation for Login page */
        $("#resetForgotFm").validate({
            rules: {
                password: {
                    required: true,
                    minlength:8,
                    maxlength:15,
                    pattern : /^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/
                },
                password_confirmation: {
                    required: true,
                    equalTo : "#password",
                }
            },
            messages: {
                password: {
                    required: messages.req_password,
                    minlength: messages.min_length,
                    maxlength : messages.max_length,
                    pattern : messages.pass_rules,
                },
                password_confirmation: {
                    required: messages.req_confirm_password,
                    equalTo: messages.same_password,
                }
            }
        });

    });
} catch (e) {
    if (typeof console !== 'undefined') {
        console.log(e);
    }
}
