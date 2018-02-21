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
            result = JSON.parse(result);
            console.log(result);
            let answerTextItem = $("#answerText");

            if(result.commentId !== false)
            {
                answerAddToPage(result['userId'], result['userName'], $(answerTextItem).val(), result.commentId);
                $(answerTextItem).val('');

                swal({
                    title: "Success",
                    text: "You answered this question",
                    icon: "success",
                });
            }else
            {
                swal({
                    title: "Error",
                    text: "Unfortunely your answer can't be posted",
                    icon: "error",
                });
            }
        });
    });
    
    function answerAddToPage(userId, userName, answerText, answerId)
    {
        $(".answer-box:first").clone()
            .insertAfter(".answer-box:first");

        let newAnswer = $(".answer-box:eq(1)");

        $(newAnswer).find("h4").text(userName);
        $(newAnswer).find("p").text(answerText);
        $(newAnswer).attr("data-answer-id", answerId);
        $(newAnswer).attr("data-user-id", userId);

        $(newAnswer).show("slow");
    }

    $("#answerQuestion").click(function () {
        if(($('#loginLogoutButton').attr('data-user')).length > 0)
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

    $(window).on('scroll', infinityScroll);

    function infinityScroll() {
        let cardsCount = $(".card_edit").length,
            answersCount = $(".answer-box").length,
            itemDistance = 0, questionId = '', whereAdd, operation;

        if(cardsCount > 0)
        {
            itemDistance = ".card_edit:last";
            whereAdd = ".card_edit";
            operation = "getMoreQuestions";
        }
        else if(answersCount > 0)
        {
            itemDistance = ".answer-box:last";
            whereAdd = ".answer-box";
            operation = "getMoreAnswers";
            questionId = $("#questionId").val();
        }
        else $(window).off("scroll");

        let distanceTop = distanceToTop(itemDistance),
            screenHeight = window.innerHeight;

        if(distanceTop <= screenHeight)
        {
            $(window).off("scroll");
            $.ajax({
                method: "POST",
                url: './controller/OperationController.php',
                data: {
                    operation: operation,
                    after: $(whereAdd).length - 1,
                    questionId: questionId
                }
            }).done(function (result) {
                result = JSON.parse(result);

                if(result.success === true)
                {
                    if (cardsCount > 0)
                        add_questions(result.questions);
                    else if (answersCount > 0)
                        addAnswers(result.answers);


                    $(window).on("scroll", infinityScroll);
                }
            });
        }
    }

    function add_questions(questions) {
        questions.forEach(function(currentValue){
            $(".card_edit:first").clone()
                .insertAfter(".card_edit:last");

            let lastCard = $(".card_edit:last");
            $(lastCard).find('h5').text(currentValue.questionTitle);
            $(lastCard).find("p").text(currentValue.questionDetails);
            $(lastCard).find(".category").html('<i class="fa fa-compass"></i> '+currentValue.questionCategory);
            $(lastCard).find("a").prop("href", 'open_question?question_id='+currentValue.questionId);

            $(lastCard).show("slow");
        });
    }

    function addAnswers(answers) {
        answers.forEach(function (answer) {
            $(".answer-box:first").clone()
                .insertAfter(".answer-box:last");

            let newAnswer = $(".answer-box:last");

            $(newAnswer).find("h4").text(answer.userEmail.split("@")[0]);
            $(newAnswer).find("p").text(answer.answerText);
            $(newAnswer).attr("data-answer-id", answer.answerId);
            $(newAnswer).attr("data-user-id", answer.userId);

            $(newAnswer).show("slow");
        });
    }
    function distanceToTop(element) {
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

    $("#loginLogoutButton").click(function () {
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
        if(($("#loginLogoutButton").attr('data-user')).length > 0)
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
                    addQuestionBoard($("#yourPostTitle").val(), $("#yourPostDetails").val(), $("#yourPostCategory").val(), result.questionId);
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

    function addQuestionBoard(title, details, category, id) {
        $(".card_edit:first").clone()
            .insertAfter(".card_edit:first");

        let newCard = $(".card_edit:eq(1)");

        $(newCard).find("h5").text(title);
        $(newCard).find("p").text(details);
        $(newCard).find(".category").html('<i class="fa fa-compass"></i> ' + category);
        $(newCard).find("a").prop("href", 'open_question?question_id=' + id);

        $(newCard).show("slow");
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
        if (validateEmail(value) !== null)
        {
            $("#emailAlert").hide()
        }else $("#emailAlert").show();
    });

    function validateEmail(email) {
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
    $("#loginLogoutButton").mouseover(function () {
        if(($(this).attr('data-user')).length > 0 )
        {
            $(this).html("<i class='fas fa-sign-out-alt'></i> Logout");
        }
    });

    $("#loginLogoutButton").mouseout(function () {
        if(($(this).attr('data-user')).length > 0)
        {
            let user = $(this).attr("data-user");
            $(this).html(user);
        }
    });
});