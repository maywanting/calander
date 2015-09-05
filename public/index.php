<?php

include_once '../sys/core/init.inc.php';

$cal = new calendar($dbo, "2015-01-02 12:00:00");

$test = new admin($dbo, "2015-01-02 12:00:00");

$page_title = "Events Calendar";
$css_files = array('style.css', 'admin.css', 'ajax.css');

include_once 'assets/common/header.inc.php';
?>

<div id="content">
<?php echo $cal->buildCalendar();?>
</div>

<p>

<?php
var_dump($_SESSION);
	echo isset($_SESSION['user']) ? "Logged In!" : "Logged Out!";
?>
</p>

<?php include_once 'assets/common/footer.inc.php';?>