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
	$postData = '[{"headNo":"1","questionNo":"1","answer":"1"},{"headNo":"1","questionNo":"2","answer":"2"},{"headNo":"1","questionNo":"3","answer":"Spain"},{"headNo":"2","questionNo":"1","answer":"2"},{"headNo":"2","questionNo":"2","answer":"3"}]';
    $class->entry($postData);
}


