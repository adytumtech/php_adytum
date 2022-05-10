<?php if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']) header("Location: noPage");

class authentication_model extends Model {

	public function __construct() {
		parent::__construct();
	}

	public function getAuthentication($data) {
		$options = ['cost' => 5];
		// $password = password_hash($data['password'], PASSWORD_BCRYPT, $options);
		$password = $data['password'];
		$sth = $this->db->prepare(
			"SELECT
				`username`,
				`password`,
				`is_active`
			FROM
				user
			WHERE
				`username`=:username"
		);
		$sth->execute(array(
			":username" => $data['username']
		));
		$o = $sth->fetch(PDO::FETCH_ASSOC);
		
		if (is_array($o)) {
			if ($o['is_active'] == 1) {
				if (password_verify($password, $o['password'])) {
					/* Valid */
					return $this->generateToken($data['username']);
				} else {
					/* Invalid */
					return array("res" => "Invalid credentials!");
				}
			} else {
				return array("res" => "User deactivated!");
			}
		} else {
			return array("res" => "Invalid credentials!");
		}
	}
	
	public function generateToken($username) {
		// query to database if credentials are correct
		// if correct return JWT token		
		return Auth::generateToken($username);
	}

	public function refreshToken($token) {
		return Auth::refreshToken($token);
	}
}