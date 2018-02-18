$(function () {
    /********************************* AJAX **************************************************/
    $("#signUpForm").submit(function (event) {
        event.preventDefault();

        let formData = new FormData(this);
        formData.append('operation', 'signUp');

        $.ajax({
            method: "POST",
            url: './controller/OperationController.php',
            data: formData,
            processData: false,
            contentType: false
        }).done(function (result) {
            result = JSON.parse(result);
            if(result.success == false)
            {
                swal({
                    title: "Erro",
                    text: "Fill up the fields correctly",
                    icon: "error"
                });
            }
            else
            {
                swal({
                    title: "Success",
                    text: "You signed up successfully",
                    icon: "success",
                    buttons:{
                        Login: true
                    }
                }).then(function () {
                    location = 'login';
                });
            }
        });
    });

    $("#loginButton").submit(function (event) {
        event.preventDefault();

        let formData = new FormData(this);
        formData.append('operation', 'login');

        $.ajax({
            method: "POST",
            url: './controller/OperationController.php',
            data: formData,
            processData: false,
            contentType: false
        }).done(function (result) {
            result = JSON.parse(result);
            if(result.success == false)
            {
                swal({
                    title: "Erro",
                    text: "Fill up the fields correctly",
                    icon: "error"
                });
            }
            else
            {
                swal({
                    title: "Success",
                    text: "You signed up successfully",
                    icon: "success",
                    buttons:{
                        Login: true
                    }
                }).then(function () {
                    location = 'login';
                });
            }
        });
    });

    $("#shareQuestion").submit(function (event) {
        event.preventDefault();
    });

    /********************************* VALIDATIONS *********************************************/
    $("#name").keyup(function () {
        let value = this.value;
        if(value.length < 3){
            $("#nameAlert").show();
        }else $("#nameAlert").hide();
    });
    
    $("#email").keyup(function () {
        let value = this.value;
        if (validate_email(value) !== null)
        {
            $("#emailAlert").hide()
        }else $("#emailAlert").show();
    });
    
    function validate_email(email) {
        return email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/);
    }

    $("#password").keyup(function () {
        let value = this.value;
        if(value.length < 6)
        {
            $("#passwordAlert").show();
        }else $("#passwordAlert").hide();
    });

    $("#password-confirm").keyup(function () {
        let value = this.value,
            originalPassword = $("#password").val();

        if(value !== originalPassword)
        {
            $("#passwordConfirmAlert").show();
        }else $("#passwordConfirmAlert").hide();
    });

    /*Enable butto SIGN UP when is everything ok*/
    $("#name, #email, #password, #password-confirm").keyup(function () {
        let nameAlertVisible = $("#nameAlert").is(":visible"),
            emailAlertVisible = $("#emailAlert").is(":visible"),
            passwordlAlertVisible = $("#passwordlAlert").is(":visible"),
            passwordConfirmAlertVisible = $("#passwordConfirmAlert").is(":visible");

        if(!nameAlertVisible && !emailAlertVisible && !passwordlAlertVisible && !passwordConfirmAlertVisible)
        {
            let name = $("#name").val(),
                email = $("#email").val(),
                password = $("#password").val(),
                passworConfirmation = $("#password-confirm").val();

            if(name.length > 0 && email.length > 0 && password.length > 0 && passworConfirmation.length > 0)
            {
                $("#signUpButton").prop("disabled", false);
            }
        }else $("#signUpButton").prop("disabled", true);
    });
});