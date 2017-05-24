<?php
require $_SERVER['DOCUMENT_ROOT'] . '/../config.php';

class Database{
	
	private $host = DB_HOST;
	private $user = DB_USER;
	private $pass = DB_PASS;
	private $dbname = DB_NAME;

	private $db;
	private $error;

	private $stmt;

	public function __construct(){
		$dsn = 'mysql:host=' . $this->host . ';port=3307;dbname=' . $this->dbname;

		$option = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);

		try{
			$this->db = new PDO($dsn, $this->user, $this->pass, $option);
		}
		catch(PDOException $e){
			echo $this->error = $e->getMessage();
		}
	}

	public function query($query){
		$this->stmt = $this->db->prepare($query);
	}

	public function bind($param, $value, $type = null){

		if(is_null($type)){
			switch (true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
					case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
					break;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}

	public function execute($params){
		return $this->stmt->execute($params);
	}

	public function resultsest(){
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function single(){
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function rowCount(){
		return $this->stmt->rowCount();
	}

}