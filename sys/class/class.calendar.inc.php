<?php

class calendar extends db_connect
{
	private $_useDate; // 当前的日期
	private $_m;
	private $_y;
	private $_daysInMonth;
	private $_startDay;  //1号是星期几

	public function __construct($dbo = NULL, $useDate = NULL)
	{ 
		parent::__construct($dbo);
	}
}
?>