<?php
include_once '../sys/core/init.inc.php';

$cal = new calendar($dbo, "2015-01-02 12:00:00");

if (is_object($cal))
{
	echo "<pre>", var_dump($cal), "</pre>";
}

?>