<?php
$DEBUG = in_array($_SERVER['SERVER_ADDR'], ['127.0.0.1']);


//error_reporting(E_ALL & ~E_NOTICE);
//error_reporting(0);

defined('YII_DEBUG') or define('YII_DEBUG', $DEBUG);
defined('YII_ENV') or define('YII_ENV', 'dev');