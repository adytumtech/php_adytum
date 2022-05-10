<?php if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']) header("Location: noPage");

function JsonEncode($array = array())
{
    header('Content-Type: application/json; charset=utf-8');
    return json_encode($array);
}