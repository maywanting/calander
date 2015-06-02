<?php
session_start();

if (!isset($_SESSION['token']))
{
	/*
	sha1()加密，sha1加密比md5更为高级点
	string uniqid ([ string $prefix = "" [, bool $more_entropy = false ]] ) 生成唯一的ID， more_entropy函数在结尾额外添加煽使得更具唯一性，说白了前一个参数是前缀，后一个参数是开启后缀的开关。
	mt_rand()效率比rand（）高四倍，产生随机数
	*/
	$_SESSION['token'] = sha1(uniqid(mt_rand(), true));
}

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
		require_once $filename;
	}
}
?>