<?php

if (!defined('DS')) {
//	define('DS', DIRECTORY_SEPARATOR);
	define('DS', '/');
}
if (!defined('ROOT_DIR')) {
	define('ROOT_DIR', '/var/www/html/Questions');
}
if (!defined('APP_ROOT_DIR')) {
//	define('DS', DIRECTORY_SEPARATOR);
	define('APP_ROOT_DIR', ROOT_DIR . '/app');
}
if (!defined('CONTROLLER_BASE_NAME')) {
	define('CONTROLLER_BASE_NAME', 'Controller.php');
}
if (!defined('API_BASE_NAME')) {
	define('API_BASE_NAME', 'Api.php');
}
if (!defined('MODEL_BASE_NAME')) {
	define('MODEL_BASE_NAME', 'Model.php');
}
if (!defined('VIEW_BASE_NAME')) {
	define('VIEW_BASE_NAME', '.tmpl');
}
if (!defined('VIEW_DIR')) {
	define('VIEW_DIR', 'view');
}
if (!defined('OUTPUT_DIR')) {
	define('OUTPUT_DIR', 'output');
}
if (!defined('LOG_DIR')) {
	define('LOG_DIR', ROOT_DIR . '/log');
}

