<?php if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']) header("Location: noPage");

class Database extends PDO {

	public function __construct($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASSWORD)
	{
	    try {
			parent::__construct($DB_TYPE.':host='.$DB_HOST.';dbname='.$DB_NAME.';charset=utf8', $DB_USER, $DB_PASSWORD);
		    $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    } catch(PDOException $e) {
	      	echo 'ERROR: ' . $e->getMessage();
	    }
	}

	public function checkExistData($db, $table, $where)
	{
		$table_name = (!empty($db)) ? $db .'.'. $table : $table;
		$sth = $this->prepare("SELECT COUNT(1) FROM $table_name WHERE $where");
		$sth->execute();
		return $sth->fetchColumn();
	}

	public function insert($db, $table, $data)
	{
		ksort($data);

		$data_value = array();
		$db_name = (!empty($db)) ? $db : DB_NAME;

		foreach ($data as $key => $value) {

			$sql = "SELECT COUNT(COLUMN_NAME) exist
			FROM information_schema.COLUMNS
			WHERE
				TABLE_SCHEMA = :db_name
			AND TABLE_NAME = :table
			AND COLUMN_NAME = :column";
			$sth = $this->prepare($sql);


			$sth->execute(array(
				"db_name" => $db_name,
				"table"   => $table,
				"column"  => $key
			));
			$res = $sth->fetchColumn();
			if($res == 1) {
				$data_value += array($key => $value);
			}
		}
		$fieldNames = implode('`, `', array_keys($data_value));
		$fieldValues = ':' . implode(', :', array_keys($data_value));

		$table_name = (!empty($db)) ? $db .'.'. $table : $table;

		try {
			$sth = $this->prepare("INSERT INTO $table_name(`$fieldNames`) VALUES($fieldValues)");
			foreach ($data_value as $key => $value) {
	
				if($this->validateDate($value) == true) {
					$value = date('Y-m-d', strtotime($value));
				}
	
				$sth->bindValue(":$key", trim($value));
			}
			$res = $sth->execute();
			$last_id = $this->lastInsertId();
	
			return array("status" => 200, "res"=> $res, "last_id"=>$last_id);
		} catch (Exception $e) {
			// return $e; // turn on only for debugging.
			return array("status" => 500, "timestamp" => date('Y-m-d H:i:s'), "error" => "Server error");
		}
	}

	public function update($db, $table, $data, $where)
	{

		ksort($data);

		$fieldDetails = NULL;

		$data_value = array();
		$db_name = (!empty($db)) ? $db : DB_NAME;

		foreach ($data as $key => $value) {

			$sql = "SELECT COUNT(COLUMN_NAME) exist
			FROM information_schema.COLUMNS
			WHERE
				TABLE_SCHEMA = :db_name
			AND TABLE_NAME = :table
			AND COLUMN_NAME = :column";
			$sth = $this->prepare($sql);
			$sth->execute(array(
				"db_name" => $db_name,
				"table"   => $table,
				"column"  => $key
			));
			$res = $sth->fetchColumn();
			if($res == 1) {
				$data_value += array($key => $value);
			}
		}

		foreach ($data_value as $key => $value) {
			$fieldDetails .= "`$key`=:$key,";
		}

		$fieldDetails = rtrim($fieldDetails, ',');

		$table_name = (!empty($db)) ? $db .'.'. $table : $table;

		try {
			$sth = $this->prepare("UPDATE $table_name SET $fieldDetails WHERE $where");
			foreach ($data_value as $key => $value) {
				if($this->validateDate($value) == true) 
					$value = date('Y-m-d', strtotime($value));
	
				$sth->bindValue(":$key", $value);
			}
			$res = $sth->execute();
			$last_id = $this->lastInsertId();
	
			return array("status" => 200,"res"=>$res, "last_id"=>$last_id);
		} catch (Exception $e) {
			// return $e; // turn on only for debugging.
			return array("status" => 500, "timestamp" => date('Y-m-d H:i:s'), "error" => "Server error");
		}

	}

	public function delete($db, $table, $where, $limit = "")
	{
		$table_name = (!empty($db)) ? $db .'.'. $table : $table;
		$sth = $this->prepare("DELETE FROM $table_name WHERE $where $limit");

		$res = $sth->execute();
		return array("res"=>$res);
	}

	public function validateDate($date, $format = 'd-m-Y')
	{
    	$d = DateTime::createFromFormat($format, $date);
    	return $d && $d->format($format) == $date;
	}
}
