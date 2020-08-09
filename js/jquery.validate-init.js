var form_validation = function () {
    var e = function () {
        jQuery(".form-valide").validate({
            ignore: ".ignore",
            errorClass: "invalid-feedback animated fadeInDown",
            errorElement: "div",
            errorPlacement: function (e, a) {
                jQuery(a).parents(".form-group > div").append(e)
            },
            highlight: function (e) {
                jQuery(e).closest(".form-group").removeClass("is-invalid").addClass("is-invalid")
            },
            success: function (e) {
                jQuery(e).closest(".form-group").removeClass("is-invalid"), jQuery(e).remove()
            },
            submitHandler: function (form) {
                var formData = new FormData(form);
                if (formData.get('formType') == 'Users') {
                    UsersForm(form);
                } else if (formData.get('formType') == 'Scans') {
                    ScansForm(form);
                } else if (formData.get('formType') == 'Extra') {
                    ExtrasForm(form);
                }
            },
            rules: {
                "terms": { required: !0 },
                "fullname": {
                    required: !0,
                    minlength: 3
                },
                "email": {
                    required: !0,
                    email: !0
                },
                "phone": {
                    required: !0,
                    minlength: 9
                },
                "position": {
                    required: true
                },
                "role": {
                    required: true
                },
                "password": {
                    required: !0,
                    minlength: 6
                },
                "current_password": {
                    required: function () {
                        return ($("#current_password").val() != "");
                    },
                    minlength: 6
                },
                "new_password": {
                    required: function () {
                        return ($("#current_password").val() != "");
                    },
                    minlength: 6

                },
                "confirm_password": {
                    equalTo: "#new_password"
                },
                "url": {
                    required: !0,
                    minlength: 3
                },
                "attack": {
                    required: true
                },
                "httpmethod": {
                    required: true
                },
                "username": {
                    required: true
                }
            },
            messages: {
                "terms": "Please agree to terms",
                "fullname": {
                    required: "Please enter a fullname",
                    minlength: "Your fullname must consist of at least 3 characters"
                },
                "email": "Please enter a valid email address",
                "password": {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 6 characters long"
                },
                "current_password": {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 6 characters long"
                },
                "new_password": "Your password must be at least 6 characters long",
                "confirm_password": "Please enter the same password as above",
                "url": {
                    required: "Please enter a URL",
                    minlength: "Your url must consist of at least 3 characters"
                },
                "position": "Please select a value!",
                "role": "Please select a value!",
                "attack": "Please select a value!",
                "phone": "Please enter a IL phone!",
                "httpmethod": "Please select a value!",
                "username": "Please enter a Username"
            }
        })
    }
    return {
        init: function () {
            e(), a(), jQuery(".js-select2").on("change", function () {
                jQuery(this).valid()
            })
        }
    }
}();
jQuery(function () {
    form_validation.init()
});