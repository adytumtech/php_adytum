<?php if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']) header("Location: noPage");

class home extends Controller {

	public function __construct() {
		parent::__construct();
	}
	
	public function index() {}
}