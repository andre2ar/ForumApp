$(function () {
    $("#answerForm").submit(function (event) {
        event.preventDefault();

        let formData = new FormData(this);
        formData.append('operation', 'answerQuestion');

        $.ajax({
            method: "POST",
            url: './controller/OperationController.php',
            data: formData,
            processData: false,
            contentType: false
        }).done(function (result) {
            $("#answerText").val('');
            swal({
                title: "Success",
                text: "You answered this question",
                icon: "success",
            });
        });
    });

    $("#answerQuestion").click(function () {
        if(($('#login_logout_button').attr('data-user')).length > 0)
        {
            $("#answerSpace").show('fast');
        }else
        {
            swal({
                title: "Login",
                text: "To post an answer you need to login first",
                icon: "info",
                buttons:{
                    cancel: "No",
                    Login: true
                }
            }).then(function (option) {
                if(option === 'Login')
                {
                    location = 'login';
                }
            });
        }
    });

    $(window).on('scroll', infinity_scroll);

    function infinity_scroll() {
        let distance_top = distance_to_top(".card_edit:last"),
            screen_height = window.innerHeight;

        if(distance_top <= screen_height)
        {
            $(window).off("scroll");
            $.ajax({
                method: "POST",
                url: './controller/OperationController.php',
                data: {
                    operation: 'getMoreQuestions',
                    after: $(".card_edit").length - 1
                }
            }).done(function (result) {
                result = JSON.parse(result);

                if(result.success === true)
                {
                    add_questions(result.questions);
                    $(window).on("scroll", infinity_scroll);
                }
            });
        }
    }

    function add_questions(questions) {
        questions.forEach(function(currentValue){
            $(".card_edit:first").clone()
                .insertAfter(".card_edit:last");

            let lastCard = $(".card_edit:last");
            $(lastCard).find('h5').text(currentValue.postTitle);
            $(lastCard).find("p").text(currentValue.postDetails);
            $(lastCard).find(".category").html('<i class="fa fa-compass"></i>'+currentValue.postCategory);
            $(lastCard).find("a").prop("href", 'open_question?question_id='+currentValue.postId);

            $(lastCard).show("slow");
        });
    }
    function distance_to_top(element) {
        element = $(element);

        let cordinates = $(element)[0].getBoundingClientRect();
        return cordinates.y;
    }
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
            if(result.success === false)
            {
                swal({
                    title: "Error",
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

    $("#loginForm").submit(function (event) {
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
                    title: "Try again",
                    text: "User or password incorrect",
                    icon: "error"
                });
            }
            else
            {
                swal({
                    title: "Success",
                    text: "Successfully logged",
                    icon: "success",
                    buttons:{
                        Ok: true
                    }
                }).then(function () {
                    location = 'home';
                });
            }
        });
    });

    $("#login_logout_button").click(function () {
        if($(this).hasClass('btn-secondary'))
        {
            swal({
                title: "Logout",
                text: "Are you sure that you want to logout?",
                icon: "info",
                buttons:{
                    cancel: "No",
                    Yes: true
                }
            }).then(function (option) {
                if(option === 'Yes')
                {
                    $.ajax({
                        method: 'POST',
                        url: "./controller/OperationController.php",
                        data:{
                            operation: 'logout'
                        }
                    }).done(function (result) {
                        result = JSON.parse(result);
                        if(result.success === true)
                        {
                            swal({
                                title: "Success",
                                text: "Successfully logged out",
                                icon: "success",
                                buttons:{
                                    Ok: true
                                }
                            }).then(function () {
                                location.reload();
                            });
                        }
                    });
                }
            });
        }
    });

    /*Define previous value to fields: before login*/
    $("#yourPostTitle").val(sessionStorage.getItem('forumAppPostTitle'));
    $("#yourPostDetails").val(sessionStorage.getItem('forumAppPostDetails'));
    $("#yourPostCategory").val(sessionStorage.getItem('forumAppPostCategory'));

    $("#clearFields").click(function () {
        sessionStorage.removeItem('forumAppPostTitle');
        sessionStorage.removeItem('forumAppPostDetails');
        sessionStorage.removeItem('forumAppPostCategory');
    });

    $("#shareQuestion").submit(function (event) {
        event.preventDefault();

        /* User is logged */
        if(($("#login_logout_button").attr('data-user')).length > 0)
        {
            let formData = new FormData(this);
            formData.append('operation', 'submitQuestion');

            $.ajax({
                method: "POST",
                url: './controller/OperationController.php',
                data: formData,
                processData: false,
                contentType: false
            }).done(function (result) {
                result = JSON.parse(result);
                if(result.questionId === false)
                {
                    swal({
                        title: "Error",
                        text: "Something happen, try again later",
                        icon: "error"
                    });
                }
                else
                {
                    add_question_board($("#yourPostTitle").val(), $("#yourPostDetails").val(), $("#yourPostCategory").val(), result.questionId);
                    $("#clearFields").click();
                    swal({
                        title: "Success",
                        text: "Successefully posted",
                        icon: "success",
                    }).then(function () {
                        $("#yourPostTitle").focus();
                    });
                }
            });
        }else
        {
            /* User need to login */
            sessionStorage.setItem('forumAppPostTitle', $("#yourPostTitle").val());
            sessionStorage.setItem('forumAppPostDetails', $("#yourPostDetails").val());
            sessionStorage.setItem('forumAppPostCategory', $("#yourPostCategory").val());

            swal({
                title: "Login",
                text: "To post you need to login first",
                icon: "info",
                buttons:{
                    cancel: "No",
                    Login: true
                }
            }).then(function (option) {
                if(option === 'Login')
                {
                    location = 'login';
                }
            });
        }
    });

    function add_question_board(title, details, category, id) {
        $(".card_edit:first").clone()
            .insertAfter(".card_edit:first");

        let new_card = $(".card_edit:eq(1)");

        $(new_card).find("h5").text(title);
        $(new_card).find("p").text(details);
        $(new_card).find(".category").html('<i class="fa fa-compass"></i>' + category);
        $(new_card).find("a").prop("href", 'open_question?question_id=' + id);

        $(new_card).show("slow");
    }

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
    if($("#signUpButton").length > 0)
    {
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
    }
    /**************************************** CSS ********************************************/
    $("#login_logout_button").mouseover(function () {
        if(($(this).attr('data-user')).length > 0 )
        {
            $(this).html("<i class='fas fa-sign-out-alt'></i> Logout");
        }
    });

    $("#login_logout_button").mouseout(function () {
        if(($(this).attr('data-user')).length > 0)
        {
            let user = $(this).attr("data-user");
            $(this).html(user);
        }
    });
});