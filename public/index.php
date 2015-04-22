<?php

include_once '../sys/core/init.inc.php';

$cal = new calendar($dbo, "2015-01-02 12:00:00");

echo $cal->buildCalendar();

?>