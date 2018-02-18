<?php
/**
 * Created by André Alvim Ribeiro
 */

include_once 'controller/Router.php';
include_once 'libraries/php/functions.php';

$requested_uri = $_SERVER['REQUEST_URI'];
$router = new Router('forumApp');
session_start();
$_SESSION['user'] = 'andre2ar@outlook.com';

$router->route($requested_uri);