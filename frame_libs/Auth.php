<?php if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']) header("Location: noPage");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends Model {
	
	private static $key = "lakldjakdfjhdsufhiuwe7y438y82u89u2398u48932u49u290uri2j398j39";
	
	public function __construct()
	{
		parent::__construct();
	}

	public static function isAuthenticated() 
	{
		$token = null;
		$headers = apache_request_headers();
		if(isset($headers['Authorization'])) {
			$auth_header = $headers['Authorization'];
		} else {
			http_response_code(401);
			echo JsonEncode(array("res" => "No Authorization header found!"));
			exit();
		}

		$token = substr($auth_header, 7);
		if (self::verifyToken($token) == 0) {
			http_response_code(401);
			echo JsonEncode(array("res" => "Invalid token"));
			exit();
		}
	}

	public static function verifyToken($jwt = "") {
		try {
			$decoded = JWT::decode($jwt, new Key(self::$key, 'HS256'));
			$decoded_array = (array) $decoded;

			if (!isset($decoded_array['exp'])) return 0;
			if (!isset($decoded_array['name'])) return 0;

			return (new self)->loadUserByUserName($decoded_array['name']);
		} catch (\Exception $e) {
			return 0;
		}
	}

	public static function generateToken($username = '') {
		$iat = new DateTimeImmutable(date('Y-m-d H:i:s'));
		$interval = 'P0Y0M0DT24H0M0S';
		$exp = $iat->add(new DateInterval($interval));
		
		$payload = array(
			"aud" => "http://app.impiloonline.com",
			"name" => $username,
			"exp" => $exp->getTimestamp(),
			"iat" => $iat->getTimestamp(),
			"nbf" => $iat->getTimestamp()
		);

		$jwt = JWT::encode($payload, self::$key, 'HS256');

		return array("token" => $jwt);
	}

	public static function refreshToken($token) {
		if (self::verifyToken($token) == 1) {
			return self::generateToken();
		} else {
			return array("res" => "Invalid Token!");
		}
	}
	
	public function loadUserByUserName($username) {
        $sth = $this->db->prepare(
            "SELECT
                COUNT(1) exist
            FROM
                user
            WHERE
                username = :username
            AND is_active = 1"
        );
        $sth->execute(array("username" => $username));
        return $sth->fetchColumn();
	}
}