<?php if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']) header("Location: noPage");

class user extends Controller {

	public function __construct() {
        Auth::isAuthenticated();
	}
	
	public function index() {}
}