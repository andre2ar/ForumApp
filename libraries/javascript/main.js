$(function () {
    $(window).on('scroll', infinityScroll);

    /********************************************** AJAX **********************************************************/
    /* Share a question */
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

    /* Send answer */
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

    /* Sign up Form*/
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

    /* Login form */
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

    /* Logout button */
    $("#loginLogoutButton").click(function () {
        if(($(this).attr('data-user')).length > 0)
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

    /* Infinity scroll: Questions and answers */
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
                        addQuestions(result.questions);
                    else if (answersCount > 0)
                        addAnswers(result.answers);


                    $(window).on("scroll", infinityScroll);
                }
            });
        }
    }

    /* Edit question */
    $("#editQuestion").submit(function (event) {
        event.preventDefault();

        let formData = new FormData(this);
        formData.append('operation', 'editQuestion');
        formData.append('questionId', $("#questionId").val());

        $.ajax({
            method: "POST",
            url: './controller/OperationController.php',
            data: formData,
            processData: false,
            contentType: false
        }).done(function (result) {
            result = JSON.parse(result);
            if(result.success === true)
            {
                $("#editQuestionArea").hide('fast');
                let questionTitle = $("#editQuestionTitle").val(),
                    questionDetails = $("#editQuestionDetails").val(),
                    questionCategory = $("#editQuestionCategory").val();

                $(".question-area h1").text(questionTitle);
                $(".question-area p").text(questionDetails);
                $(".category span").text(questionCategory);

                swal({
                    title: "Success",
                    text: "Question edited successefully",
                    icon: "success",
                });
            }else
            {
                swal({
                    title: "Error",
                    text: "Question couldn't be edited successefully",
                    icon: "error",
                });
            }
        });
    });

    /* Edit comment */
    $(document).on("click",".saveEditAnswerButton", function () {
        let answerId = $(this).attr("data-answer-id"),
            answerEdited = $("textarea[data-answer-id='"+answerId+"']").val();

        let buttonParent  = $(this).parent().parent().parent();

        console.log($("p[data-answer-id='"+answerId+"'] span").length);
        $.ajax({
            method: "POST",
            url: './controller/OperationController.php',
            data: {
                operation: "editAnswer",
                answerId: answerId,
                answerText: answerEdited
            }
        }).done(function (result) {
            result = JSON.parse(result);
            if(result.success === true)
            {
                $("p[data-answer-id='"+answerId+"'] span").text($("textarea[data-answer-id='"+answerId+"']").val());
                $("p[data-answer-id='"+answerId+"']").show("fast");
                $("div[data-answer-id='"+answerId+"'] .editAnswerArea").hide('fast');

                swal({
                    title: "Success",
                    text: "Answer edited successefully",
                    icon: "success",
                });
            }else
            {
                swal({
                    title: "Error",
                    text: "Answer couldn't be edited successefully",
                    icon: "error",
                });
            }
        });
    });

    /********************************************** Auxiliar ********************************************************/
    /* Add questions dinamically*/
    function addQuestions(questions) {
        questions.forEach(function(currentValue){
            $(".card_edit:first").clone()
                .insertAfter(".card_edit:last");

            let lastCard = $(".card_edit:last");
            $(lastCard).find('h5').text(currentValue.questionTitle);
            $(lastCard).find("p").text(currentValue.questionDetails);
            $(lastCard).find(".category span").text(currentValue.questionCategory);
            $(lastCard).find("a").prop("href", 'open_question?question_id='+currentValue.questionId);

            $(lastCard).show("slow");
        });
    }

    function addQuestionBoard(title, details, category, id) {
        $(".card_edit:first").clone()
            .insertAfter(".card_edit:first");

        let newCard = $(".card_edit:eq(1)");

        $(newCard).find("h5").text(title);
        $(newCard).find("p").text(details);
        $(newCard).find(".category span").text(category);
        $(newCard).find("a").prop("href", 'open_question?question_id=' + id);

        $(newCard).show("slow");
    }

    /* Add answers dinamically*/
    function answerAddToPage(userId, userName, answerText, answerId) {
        let currentUserId = $("#loginLogoutButton").attr('data-user-id');
        $(".answer-box:first").clone()
            .insertAfter(".answer-box:first");

        let newAnswer = $(".answer-box:eq(1)");

        $(newAnswer).find("h4").text(userName);
        $(newAnswer).find("p span").text(answerText);
        $(newAnswer).find("p").attr("data-answer-id", answerId);
        $(newAnswer).attr("data-answer-id", answerId);
        $(newAnswer).find('button').attr("data-answer-id", answerId);
        $(newAnswer).attr("data-user-id", userId);

        if(userId === currentUserId)
        {
            $(newAnswer).find('button').attr("data-answer-id", answerId);
            $(newAnswer).find('div').attr("data-answer-id", answerId);
            $(newAnswer).find('div textarea').attr("data-answer-id", answerId);
            $(newAnswer).find('div textarea').val(answerText);

            $(newAnswer).find('button').show();
        }

        $(".alert").hide();

        $(newAnswer).show("slow");
    }

    function addAnswers(answers) {
        let currentUserId = $("#loginLogoutButton").attr('data-user-id');
        answers.forEach(function (answer) {
            $(".answer-box:first").clone()
                .insertAfter(".answer-box:last");

            let newAnswer = $(".answer-box:last");

            $(newAnswer).find("h4").text(answer.userEmail.split("@")[0]);
            $(newAnswer).find("p span").text(answer.answerText);
            $(newAnswer).find("p").attr("data-answer-id", answer.answerId);
            $(newAnswer).attr("data-answer-id", answer.answerId);
            $(newAnswer).find('button').attr("data-answer-id", answer.answerId);
            $(newAnswer).attr("data-user-id", answer.userId);

            if(answer.userId === currentUserId)
            {
                $(newAnswer).find('button').attr("data-answer-id", answer.answerId);
                $(newAnswer).find('div').attr("data-answer-id", answer.answerId);
                $(newAnswer).find('div textarea').attr("data-answer-id", answer.answerId);
                $(newAnswer).find('div textarea').val(answer.answerText);

                $(newAnswer).find('button').show();
            }

            $(newAnswer).show("slow");
        });
    }

    /* Redirect user to login page if not logged */
    $("#answerQuestion").click(function () {
        $("#editQuestionArea").hide("fast");
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

    /* Show form to edit a question */
    $("#editQuestionButton").click(function () {
        $("#answerSpace").hide("fast");
        $("#editQuestionArea").show("slow");
    });

    /* Show form to edit answer */
    $(document).on("click", ".editAnswerButton",function () {
        let answerId = $(this).attr("data-answer-id");
        $("p[data-answer-id='"+answerId+"']").hide("fast");
        $("div[data-answer-id='"+answerId+"']").show("fast");
    });

    /* Calculates the distance to the top of page */
    function distanceToTop(element) {
        element = $(element);

        let cordinates = $(element)[0].getBoundingClientRect();
        return cordinates.y;
    }

    /* Clear question form and SESSION STORAGE */
    $("#clearFields").click(function () {
        sessionStorage.removeItem('forumAppPostTitle');
        sessionStorage.removeItem('forumAppPostDetails');
        sessionStorage.removeItem('forumAppPostCategory');
    });

    /*Define previous value to fields: before login*/
    $("#yourPostTitle").val(sessionStorage.getItem('forumAppPostTitle'));
    $("#yourPostDetails").val(sessionStorage.getItem('forumAppPostDetails'));
    $("#yourPostCategory").val(sessionStorage.getItem('forumAppPostCategory'));

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
});