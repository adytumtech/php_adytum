<?php if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']) header("Location: noPage");

class Session {

	public static function init() {
		session_start();
	}

	public static function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	public static function get($key)
	{
		if(isset($_SESSION[$key]))
			return $_SESSION[$key];	
	}

	public static function destroy($key)
	{
		if(isset($key))
			unset($_SESSION[$key]);
		session_destroy();
	}
}