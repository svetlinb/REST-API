<?php
abstract class DBManager {
	protected $dbh;
	protected $dbname = "rest-api";
	protected $user = "root";
	protected $pass = "";
	
	/*
	 * Handle DB connection
	 */
	public function __construct() {
		try {
			// MySQL with PDO_MYSQL
			$this->dbh = new PDO("mysql:host=localhost;dbname=$this->dbname", $this->user, $this->pass);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	protected abstract function get($id);

	protected abstract function post();

	protected abstract function put($id);

	protected abstract function delete($id);
	
	protected abstract function getList();
	
}

?>