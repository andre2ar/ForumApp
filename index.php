<?php
/**
 * Created by André Alvim Ribeiro
 */

include_once 'controller/Router.php';
include_once 'libraries/php/functions.php';

$requested_uri = $_SERVER['REQUEST_URI'];
$router = new Router('forumApp');

session_start();

if(isset($_COOKIE['logged_user']))
{
    if(!isset($_SESSION['logged_user']))
    {
        $_SESSION['logged_user'] = $_COOKIE['logged_user'];
        $_SESSION['user_id'] = $_COOKIE['user_id'];
    }
}

$router->route($requested_uri);