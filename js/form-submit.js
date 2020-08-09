function UsersForm(form) {
    var formData = new FormData(form);
    //IF Edit Fill Passwords Fields
    if (formData.get('formID') == 'Edit') {
        if (formData.get('current_password') == '') {
            formData.set('current_password', "NOPASSWORD")
            formData.set('new_password', "NOPASSWORD")
        }
    }
    $.ajax({
        url: "../helpers/UsersForm.php",
        type: 'POST',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {

            //Register
            if (response == 'Register') {
                UserAlertSuccessVI("User Created", "Please Login");
                $('#myform')[0].reset();

            }
            //Create account
            else if (response == 'Created') {
                UserToastSuccess("User Created", "Enjoy!");
                $('#myform')[0].reset();

            } else if (response == 'Exists') {
                UserAlertError("Email Address Exists", "Please choose a different Email Address");
            }
            //Login
            else if (response == 'NoLogin') {
                UserToastError("Email or Password incorrect", "Please enter valid credentials");
            } else if (response == 'Login') {
                $('#myform')[0].reset();
                location.href = "../scans/scan.php"

            }

            //Edit Your Account
            else if (response == 'PasswordError') {
                UserToastError("Current Password incorrect", "Please enter your Current Password");
            } else if (response == 'ErrorUpdated') {
                UserToastError("Update Error", "Please enter valid details");
            } else if (response == 'EditUpdated') {
                UserToastSuccess("User Updated", "Enjoy!");
            }
        }
    });


}

function ScansForm(form) {
    var formData = new FormData(form);
    //IF Edit Fill Passwords Fields
    $.ajax({
        url: "../helpers/ScansForm.php",
        type: 'POST',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {

            //Create Scan
            if (response == 'Created') {
                location.href = "../scans/history.php"

            } else if (response == 'ErrorUpdated') {
                UserToastError("Scan Error", "Please enter valid details");
            }
        }
    });


}


function ExtrasForm(form) {
    var formData = new FormData(form);
    //IF Edit Fill Passwords Fields
    $.ajax({
        url: "../helpers/ExtraForm.php",
        type: 'POST',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {

            //Create Scan
            if (response == 'Created') {
                UserToastSuccess("Message Received", "We will be in touch shortly!");

            } else if (response == 'Error') {
                UserToastError("Contact Us Error", "Please enter valid details");
            }
        }
    });


}