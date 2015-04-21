<?php

include_once '../sys/config/db-cred.inc.php'; //include_once() 包含并运行文件，如果被包含过则不会再次包含

foreach ($C as $name => $val) {
	define ($name, $val); //define(name, value, case_insensitive) 定义一个常量，常量的使用可以不用$
}

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
$dbo = new PDO($dsn, DB_USER,DB_PASS);

function __autoload($class)
{
	$filename = "../sys/class/class." . $class . ".inc.php";

	if (file_exists($filename)) //file_exits(path) 检查文件或目录是否存在。
	{
		include_once $filename;
	}
}
?>