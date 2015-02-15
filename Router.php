<?php
class Router {
	private $params;
	private $method;
	private $action;
	private $id;
	
	/*
	 * Send needed headers.
	 * Init variables and validate user input.
	 */
	public function __construct(){
		header('Content-Type: text/javascript; charset=utf8');
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        header("Content-Type: application/json");
        
		if($_SERVER['QUERY_STRING']) {
		    $this->method = strtolower($_SERVER["REQUEST_METHOD"]);
			$this->params = explode("/", $_SERVER['QUERY_STRING']);
			$this->sanitizeInput();
			$this->initVars();
			
		  if(!$this->validateInput()) {
		  	Response::sendHeader("Forbidden", 403);
		  }
		  
		}
	}
	
	/*
	 * Process request
	 */
	public function process() {
	  try {
		$this->invokeAction();
	  }catch(Exception $e){
	  	Response::sendHeader($e->getMessage(), $e->getCode());
	  }
	}	

	/*
	 * Invoke required action
	 */
	private function invokeAction() {
		$model = $this->getModel();
		$dbClass = new $model();
		$action = $this->action;
		
		if(method_exists($dbClass, $action)) {
			$dbClass->$action($this->id);
		}else {
			throw new Exception('Not found', 404);
		}
	}
	
	/*
	 * Get needed model
	 */
	private function getModel() {
		$class = ucfirst($this->params[0]);
		
		if(!class_exists($class)) {
			throw new Exception('Not found', 404);
		}
		
	  return $class;
	}
	
	/*
	 * Sanitize user input
	 */
	private function sanitizeInput(){
		$this->params = is_array($this->params) ? filter_var_array($this->params, FILTER_SANITIZE_STRING)
						: filter_var(trim($this->params), FILTER_SANITIZE_STRING);
		$this->params = array_filter($this->params);
	} 
	
	/*
	 * Check argument range
	 */
	private function validateInput() {
		$paramCnt = count($this->params);
		
		return !($paramCnt < 1 || $paramCnt > 3);
	}
	
	/*
	 * Init variables
	 */
	private function initVars() {
	 	$this->id = (is_numeric($this->params[1]) && !empty($this->params[1])) ? @$this->params[1] : @$this->params[2];
		$this->action = (!is_numeric($this->params[1]) && !empty($this->params[1])) ? $this->method.ucfirst($this->params[1]) : $this->method;
		
	}
	
}


?>