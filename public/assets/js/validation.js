
$(document).ready(function () {
    $("#LoginForm").validate({
        onfocusout: false,
        errorClass: 'js_error',
        rules: {
            email_address: {
                required: true,
                email: true
            },
            password: {
                required: true,
                validPassword: true
            }
        },
        messages: {
            email_address: {
                required: "Please enter email",
                email: "Please enter a valid email address",
            },
            password: {
                required: "Please enter password",
            },
        },

        submitHandler: function(form) {
            form.submit(); 
        },
    });

    // $("#ForgotPasswordOtp").validate({
    //     onfocusout: false,
    //     errorClass: 'js_error',
    //     rules: {
    //         otp1: {
    //             required: true
    //         },
    //         otp2: {
    //             required: true
    //         },
    //         otp3: {
    //             required: true
    //         },
    //         otp4: {
    //             required: true
    //         },
    //         otp5: {
    //             required: true
    //         },
    //         otp6: {
    //             required: true
    //         }
    //     },
    //     messages: {
    //         otp1: {
    //             required: "Please enter first otp degit"
    //         },
    //         otp2: {
    //             required: "Please enter second otp degit",
    //         },
    //         otp3: {
    //             required: "Please enter third otp degit"
    //         },
    //         otp4: {
    //             required: "Please enter fourth otp degit",
    //         },
    //         otp5: {
    //             required: "Please enter fifth otp degit"
    //         },
    //         otp6: {
    //             required: "Please enter sixth otp degit",
    //         },
    //     },

    //     submitHandler: function(form) {
    //         form.submit(); 
    //     },
    // });

    $("#ForgotPasswordForm").validate({
        onfocusout: false,
        errorClass: 'js_error',
        rules: {
            email_address: {
                required: true,
                email: true
            }
        },
        messages: {
            email_address: {
                required: "Please enter email",
                email: "Please enter a valid email address",
            }
        },

        submitHandler: function(form) {
            form.submit(); 
        },
    });

    $("#CreateNewPassForm").validate({
        onfocusout: false,
        errorClass: 'js_error',
        rules: {
            password: {
                required: true
            },
            confirm_password: {
                required: true,
                equalTo: "#password" 
            }
        },
        messages: {
            password: {
                required: "Please enter new password",
            },
            confirm_password: {
                required: "Please enter same as new password",
                equalTo: "Passwords do not match"
            },
        },

        submitHandler: function(form) {
            form.submit(); 
        },
    });
});