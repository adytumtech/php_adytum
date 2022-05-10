<?php if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']) header("Location: noPage");

if(isset($_SERVER["REQUEST_SCHEME"]))
	$rs = $_SERVER["REQUEST_SCHEME"];
else
	$rs = "http";

define('URL_ADDRESS', $rs. '://'. $_SERVER['HTTP_HOST']);
define('URL', $rs. '://'. $_SERVER['HTTP_HOST'] . substr($_SERVER['PHP_SELF'], 0, -9));

$url = isset($_GET['url']) ? $_GET['url'] : null;
$url = rtrim($url,'/');
$url = explode('/',$url);
define('CURRENT_URI', $url[0]);