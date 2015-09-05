<?php
session_start();

include_once '../../../sys/config/db-cred.inc.php';

function __autoload ($class_name)
{
	$filename = '../../../sys/class/class.' . strtolower($class_name) . '.inc.php';
	if (file_exists($filename))
	{
		include_once $filename;
	}
}

foreach ($C as $name => $val) {
	define($name, $val);
}

$actions = array(
		'event_edit' => array(
				'object' => 'calendar',
				'method' => 'processForm',
				'header' => 'Location: ../../'
			),
		'user_login' => array(
				'object' => 'admin',
				'method' => 'processLoginForm',
				'header' => 'Location: ../../'
			),
		'user_logout' => array(
				'object' => 'admin',
				'method' => 'processLogout',
				'header' => 'Location: ../../'
			)
		);

if ($_POST['token']==$_SESSION['token'] && isset($actions[$_POST['action']]))
{
	$use_array = $actions[$_POST['action']];
	$obj = new $use_array['object']($dbo);

	if (TRUE === $msg = $obj->$use_array['method']())
	{
		header($use_array['header']);
		exit;
	}
	else 
	{
		die($msg);
	}
}
else
{
	header("Location: ../../");
 	exit;
}

?>