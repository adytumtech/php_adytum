<?php if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']) header("Location: noPage");

class Router {

	function __construct() {

		$url = isset($_GET['url']) ? $_GET['url'] : null;
		$url = rtrim($url,'/');
		$url = explode('/',$url);

		// print_r($url);

		if(empty($url[0])) {
			require '../module/home/home.php';
			$controller = new home();
			$controller->loadModel('home');
			$controller->index();
			return false;
		}

		$file = '../module/'. $url[0] .'/'. $url[0] .'.php';

		if(file_exists($file)) {
			require $file;
		} else {
			$this->noPage();
			return false;
		}

		$controller = new $url[0];
		$controller->loadModel($url[0]);

		if(isset($url[2])) {
			if(method_exists($controller, $url[1])) {
				$controller->{$url[1]}($url[2]);				
			} else {
				$this->noPage();
				return false;
			}
		} else {
			if(isset($url[1])) {
				if(method_exists($controller, $url[1])) {
					$controller->{$url[1]}();
				} else {
					$this->noPage();
					return false;
				}
			} else {
				$controller->index();
			}
		}
	} // end function __construct

	function noPage() {
		require '../module/page404/page404.php';
		$controller = new page404();
		$controller->index();
		return false;
	}

}