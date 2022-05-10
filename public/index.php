<?php if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']) header("Location: noPage");

require '../frame_libs/DotEnv.php';
require '../config/paths.php';
require '../config/database.php';

require '../third_party/vendor_jwt/autoload.php';
require '../frame_libs/JsonEncode.php';

function my_autoloader($class) {
	if (file_exists("../frame_libs/". $class .".php")) {
		include "../frame_libs/". $class .".php";
	}
}

spl_autoload_register('my_autoloader');

new Router();