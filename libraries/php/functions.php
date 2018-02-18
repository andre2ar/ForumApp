<?php
function load_view($view_name, $params = null)
{
    include './view/site.php';
}

function load_model($model_name, $params = null)
{
    include './controller/'.$model_name.'.php';
}