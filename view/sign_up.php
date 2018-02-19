<div id="signUp">
    <form id="signUpForm" class="form-horizontal" role="form" method="POST">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <h2 class="text-center">Sign-up</h2>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="name">Name</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <div class="input-group-addon" style="width: 2.6rem"><i class="fa fa-user"></i></div>
                        <input type="text" name="name" class="form-control" id="name"
                               placeholder="John Doe" required autofocus>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div id="nameAlert" class="form-control-feedback">
                        <span class="text-danger align-middle">
                            <i class="fa fa-close">Your name need to have at least 3 characters</i>
                        </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="email">E-Mail Address</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <div class="input-group-addon" style="width: 2.6rem"><i class="fa fa-at"></i></div>
                        <input type="text" name="email" class="form-control" id="email"
                               placeholder="you@example.com" required autofocus>
                    </div>
                </div>
            </div>
            <div  class="col-md-3">
                <div id="emailAlert" class="form-control-feedback" style="display: none;">
                        <span class="text-danger align-middle">
                            <i class="fa fa-close">Invalid e-mail</i>
                        </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="password">Password</label>
            </div>
            <div class="col-md-6">
                <div class="form-group has-danger">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <div class="input-group-addon" style="width: 2.6rem"><i class="fa fa-key"></i></div>
                        <input type="password" name="password" class="form-control" id="password"
                               placeholder="Password" required>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div id="passwordAlert" class="form-control-feedback" style="display: none;">
                        <span class="text-danger align-middle">
                            <i class="fa fa-close"> Your password need to have at least 6 characters</i>
                        </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="password">Confirm Password</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <div class="input-group-addon" style="width: 2.6rem">
                            <i class="fa fa-repeat"></i>
                        </div>
                        <input type="password" name="password-confirmation" class="form-control"
                               id="password-confirm" placeholder="Password" required>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div id="passwordConfirmAlert" class="form-control-feedback" style="display: none;">
                        <span class="text-danger align-middle">
                            <i class="fa fa-close"> Passwords doesn't match</i>
                        </span>
                </div>
            </div>
        </div>
    </form>
    <div class="text-center">
        <a href="login">I have an account!</a>
    </div>

</div>
<button form="signUpForm" disabled id="signUpButton" type="submit" class="btn btn-primary btn-lg float-right">Register</button>