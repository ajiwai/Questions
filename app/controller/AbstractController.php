<?php
require APP_ROOT_DIR . '/dao/DbConnection.php';

abstract class AbstractController {

	protected $className = "";

  	public function __construct() {
  		$this->className = str_replace(CONTROLLER_BASE_NAME,'' ,basename(__FILE__));
  	}

	public function view($postData){
		require APP_ROOT_DIR . '/view' . DS . $this->className . '.tmpl';
	}
}


