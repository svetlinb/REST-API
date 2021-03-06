<?php
class Jobs extends DBManager {
	
	public function _construct() {
		parent::_construct();
	}
	
	public function get($id) {
		$checkId = $this->isIdExist($id);
		if(!$checkId) {
			throw new Exception("Not Found", 404);
		}
		$stmt = $this->dbh->prepare("SELECT * FROM jobs WHERE id=:id ");
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute(array(":id"=>$id));
		$row = $stmt->fetch();
		
	    Response::sendHeader($row, 200);
	}

	public function post() {
		if(empty($_POST)) {
			throw new Exception("Invalid arguments", 400);
		}
	
		$query = "INSERT INTO jobs SET position = :position, description = :description";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(':position', $_POST['position'], PDO::PARAM_STR);
		$stmt->bindParam(':description', $_POST['description'], PDO::PARAM_STR);
		$stmt->execute();
		
	  	Response::sendHeader("Created", 201);
	}
	
	public function put($id) {
		$checkId = $this->isIdExist($id);
		parse_str(file_get_contents('php://input'), $data);
		if(!$checkId || empty($data)) {
			throw new Exception("Not Found", 404);
		}
		
		$query = "UPDATE jobs SET position = :position, description = :description WHERE id = :id";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(':position', $data['position'], PDO::PARAM_STR);
		$stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		
		Response::sendHeader("Updated", 200);
	}
	
	public function delete($id) {
		$checkId = $this->isIdExist($id);
		if(!$checkId) {
			throw new Exception("Not Found", 404);
		}

		$stmt = $this->dbh->prepare("DELETE FROM jobs WHERE id = :id ");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		
		Response::sendHeader("Deleted", 200);
	}
	
	public function getList() {
		$query = 'SELECT * FROM jobs ORDER BY id ASC';
		$stmt = $this->dbh->query($query);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$rows = $stmt->fetchAll();
		
		if(empty($rows)) {
			throw new Exception("There is no records.", 404);
		}
		
	  
		Response::sendHeader($rows, 200);
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
		
		$stmt = $this->dbh->prepare("SELECT * FROM jobs WHERE id=:id ");
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute(array(":id"=>$id));
		$row = $stmt->fetch();
		
		return !empty($row);
	}
}

?>