<?php if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']) header("Location: noPage");

class View {

	function __construct() {}

	public function render($mod, $file, $noInclude = false)
	{
		if($noInclude == true) {
			require 'views/'. $mod . '/'. $file .'.php';
		} else {
			require 'views/header.php';
			require 'views/'. $mod . '/'. $file .'.php';
			require 'views/footer.php';
		}
		
	}
}
