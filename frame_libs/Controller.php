<?php if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']) header("Location: noPage");

class Controller {

	function __construct() {
		$this->view = new View();
	}

	public function loadModel($name) {

		$path = '../module/'. $name .'/'. $name .'_model.php';
		if(file_exists($path)) {
			require $path;
			$modelName = $name.'_model';
			$this->model = new $modelName();
		}
	}

} // end class controller