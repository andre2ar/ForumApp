<?php
/**
 * Created by André Alvim Ribeiro
 */

include_once 'controller/Router.php';
include_once 'libraries/php/functions.php';

date_default_timezone_set('America/Sao_Paulo');

$requested_uri = $_SERVER['REQUEST_URI'];
$router = new Router('forumApp');

session_start();

if(isset($_COOKIE['forumAppLoggedUser']))
{
    if(!isset($_SESSION['forumAppLoggedUser']))
    {
        $_SESSION['forumAppLoggedUser'] = $_COOKIE['forumAppLoggedUser'];
        $_SESSION['forumAppUserId'] = $_COOKIE['forumAppUserId'];
    }
}

$router->route($requested_uri);