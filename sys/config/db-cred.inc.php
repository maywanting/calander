<?php

//报告所有错误
error_reporting(E_ALL^E_NOTICE);

ini_set("display_errors","on");

//数据库配置文件
$C = array();
$C['DB_HOST'] = 'localhost';
$C['DB_USER'] = 'root';
$C['DB_PASS'] = 'z';
$C['DB_NAME'] = 'calander';
$C['DB_ERROR_REPORT'] = true;
?>