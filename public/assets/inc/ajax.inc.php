<?php
session_start();

include_once '../../../sys/config/db-cred.inc.php';

foreach ($C as $name => $val) {
	define($name, $val);
}

$actions = array(
		'event_view' => array(
			'object' => 'calendar',
			'method' => 'displayEvent'
		)	
	);

if (isset($actions[$_POST['action']])) {
	$use_array = $actions[$_POST['action']];
	$obj = new $use_array['object']($dbo);

	if (isset($_POST['event_id'])) {
		$id = (int)$_POST['event_id'];
	} else {
		$id = NULL;
	}
	echo $obj->$use_array['method']($id);
}

function __autoload($class_name) {
	$filename = '../../../sys/class/class.' . strtolower($class_name) . '.inc.php';

	if (file_exists($filename)) {
		include_once $filename;
	}
}
?>