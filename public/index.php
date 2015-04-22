<?php

include_once '../sys/core/init.inc.php';

$cal = new calendar($dbo, "2015-01-02 12:00:00");

$page_title = "Events Calendar";
$css_files = array('style.css');

include_once 'assets/common/header.inc.php';
//echo $cal->buildCalendar();
?>

<div id="content">
<?php echo $cal->buildCalendar();?>
</div>

<?php include_once 'assets/common/footer.inc.php';?>