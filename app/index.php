<?php

require_once 'init.php';

$requestUri = str_replace(basename(dirname(__FILE__)) ,"" ,(isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : "" );
$requestUri = str_replace("word_cnt_get" ,"" ,$requestUri);
$requestUri = str_replace(basename(dirname(__FILE__)) ,"" ,$requestUri);
$requestUri = str_replace(DS ,"" ,$requestUri);
$pageName = (isset($_GET['p'])) ? $_GET['p'] : 'Questions';
$funcName = (isset($_GET['f'])) ? $_GET['f'] : '';

// $referer = $_SERVER['HTTP_REFERER'];
//echo "requestUri=".$requestUri . "<br>\n";

if($pageName == 'Api'){

}else{
    require APP_ROOT_DIR . DS . 'controller' . DS . $pageName .CONTROLLER_BASE_NAME;
    $className = $pageName . 'Controller';
    $class = new $className();
    $postData = '';
    if(isset($_POST)){
        $postData = $_POST;
    }
    
    switch($funcName){
    case 'entry':
        $postData = file_get_contents('php://input');
        $class->entry($postData);
        break;
    case 'update':
        $class->update($postData);
        break;
    case 'delete':
        $class->delete($postData);
        break;
    default:
        $class->view($postData);
        break;
    }
}


