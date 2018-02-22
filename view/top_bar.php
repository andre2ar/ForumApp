<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="home">Share questions</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="home"><i class="fa fa-home"></i> Home <span class="sr-only">(current)</span></a>
            </li>
        </ul>

        <?php
            if(isset($_COOKIE['forumAppLoggedUser']))
            {
                $user = explode("@", $_COOKIE['forumAppLoggedUser'])[0];
            }
        ?>
        <a href="<?php if(isset($user)) echo 'javascript:void(0)'; else echo 'login';?>">
            <button id="loginLogoutButton"
                    class="btn <?php if (isset($user)) echo 'btn-secondary'; else echo 'btn-outline-light';?>"
                    data-user="<?php if(isset($user)) {echo "<i class='fas fa-user'></i> "; echo $user;} ?>"
                    data-user-id="<?php if(isset($_SESSION) && isset($_SESSION['forumAppUserId']))echo $_SESSION['forumAppUserId']; ?>"
            >
                <?php
                if(isset($user))
                {
                    echo "<i class='fas fa-user'></i> ";
                    echo $user;
                }
                else echo '<i class="fas fa-sign-in-alt"></i> Login';
                ?>
            </button>
        </a>
    </div>
</nav>