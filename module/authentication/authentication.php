<?php if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']) header("Location: noPage");

class authentication extends Controller {

	public function __construct() {
		parent::__construct();
	}
	
	public function index() {}

    public function getAuthentication() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = json_decode(file_get_contents("php://input"), true);
			echo JsonEncode($this->model->getAuthentication($data));
		} else {
			http_response_code(500);
			echo JsonEncode(array("res" => "Invalid method"));
		}
	}

	public function refreshToken() {
		$jwt = json_decode(file_get_contents("php://input"), true);
		
		if (!isset($jwt['token'])) {
			http_response_code(500);
			echo JsonEncode(array("res" => "Invalid token"));
			exit();
		}

		http_response_code(200);
		echo JsonEncode($this->model->refreshToken($jwt['token']));
	}
}