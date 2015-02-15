<?php
class Candidates extends DBManager {

	public function _construct() {
		parent::_construct();
	}
	
	public function get($id) {
		$checkId = $this->isIdExist($id);
		if(!$checkId) {
			throw new Exception("Not Found", 404);
		}
		$stmt = $this->dbh->prepare("SELECT * FROM candidates WHERE id=:id ");
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute(array(":id"=>$id));
		$row = $stmt->fetch();
	
		Response::sendHeader($row, 200);
	}
	
	public function post() {
		if(empty($_POST)) {
			throw new Exception("Invalid arguments", 400);
		}
	
		$query = "INSERT INTO candidates SET position = :position, name = :name";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(':position', $_POST['position'], PDO::PARAM_STR);
		$stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
		$stmt->execute();
	
		Response::sendHeader("Created", 201);
	}
	
	public function put($id) {
		$checkId = $this->isIdExist($id);
		parse_str(file_get_contents('php://input'), $data);
		if(!$checkId || empty($data)) {
			throw new Exception("Not Found", 404);
		}
	
		$query = "UPDATE candidates SET position = :position, name = :name WHERE id = :id";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(':position', $data['position'], PDO::PARAM_STR);
		$stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
	
		Response::sendHeader("Updated", 200);
	}
	
	public function delete($id) {
		$checkId = $this->isIdExist($id);
		if(!$checkId) {
			throw new Exception("Not Found", 404);
		}
	
		$stmt = $this->dbh->prepare("DELETE FROM candidates WHERE id = :id ");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
	
		Response::sendHeader("Deleted", 200);
	}
	
	public function getList() {
		$query = 'SELECT * FROM candidates ORDER BY id ASC';
		$stmt = $this->dbh->query($query);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$rows = $stmt->fetchAll();
	
		if(empty($rows)) {
			throw new Exception("There is no records.", 404);
		}
	
		 
		Response::sendHeader($rows, 200);
	}

	public function getReview($id) {
		$checkId = $this->isIdExist($id);
		if(!$checkId) {
			throw new Exception("Not Found", 404);
		}
		$stmt = $this->dbh->prepare("SELECT * FROM candidates WHERE id=:id ");
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute(array(":id"=>$id));
		$row = $stmt->fetch();
	
		Response::sendHeader($row, 200);
	}

	public function getSearch($id) {
		$checkId = $this->isIdExist($id);
		if(!$checkId) {
			throw new Exception("Not Found", 404);
		}
		$stmt = $this->dbh->prepare("SELECT * FROM candidates WHERE id=:id ");
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute(array(":id"=>$id));
		$row = $stmt->fetch();
	
		Response::sendHeader($row, 200);
	}
	
	
	/*
	 * Check is record exist in DB
	*
	* @Return Boolean
	*/
	private function isIdExist($id) {
		if(!$id){
			return false;
		}
	
		$stmt = $this->dbh->prepare("SELECT * FROM candidates WHERE id=:id ");
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute(array(":id"=>$id));
		$row = $stmt->fetch();
	
		return !empty($row);
	}
}

?>