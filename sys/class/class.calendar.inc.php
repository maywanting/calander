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

		$this->_useDate = (isset($useDate)) ? $useDate : date('Y-m-d H:i:s');

		$ts = strtotime($this->_useDate); //strtotime(time) 将时间字符串转化成Unix时间戳
		$this->_m = date('m', $ts);
		$this->_y = date('Y', $ts);

		//cal_days_in_month(calendar, month, year)字面意思，计算某年某月有多少天
		$this->_daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->_m, $this->_y); //CAL_GREGORIAN : Gregorian calendar俗称公历
		
		$ts = mktime(0, 0, 0, $this->_m, 1, $this->_y); //mktime(hour,minute,second,month,day,year)返回一个日期的时间戳
		$this->_startDay = date('w', $ts);
	}

	private function _loadEventData($id = NULL)
	{
		$sql = "SELECT
					`event_id`, `event_title`, `event_desc`, `event_start`, `event_end`
				FROM `events`";

		if (!empty($id))
		{
			$sql .= "WHERE `event_id`=:id LIMIT 1";
		}
		else
		{
			$start_ts = mktime(0, 0, 0, $this->_m, 1, $this->_y);
			$end_ts = mktime(23, 59, 59, $this->_m, $this->_daysInMonth, $this->_y);
			$start_date = date('Y-m-d H:i:s', $start_ts);
			$end_date = date('Y-m-d H:i:s', $end_ts);

			$sqi .= "WHERE `event_start` BETWEEN '$start_date' AND '$end_date' ORDER BY `event_start`";
		}

		try
		{
			$stmt = $this->db->prepare($sql); //prepare()准备要执行的SQL语句

			if (!empty($id))
			{
				/*boolean PDOStatement::bindParam(mixed parameter,mixed &variable[,int datatype[,int length[,mixed driver_options]]])
					parameter 预处理语句中指定列值占位符的名字
					variable 赋给占位符的引用值
					datatype：显示地设置参数的SQL数据类型 PDO_PARAM_BOOL：SQL BOOLEAN类型。
							PDO_PARAM_INPUT_OUTPUT：参数传递给存储过程时使用此类型，因此，可以在过程执行后修改。
							PDO_PARAM_INT：SQL INTEGER数据类型。
							PDO_PARAM_NULL：SQL NULL数据类型。
							PDO_PARAM_LOB：SQL大对象数据类型。
							PDO_PARAM_STMT：PDOStatement对象类型，当前不可操作。
							PDO_PARAM_STR：SQL CHAR、VARCHAR和其它字符串数据类型。
					length 指定数据类型长度，只有当赋值为PDO_PARAM_INPUT_OUTPUT才需要
					driver_option 传递任何数据库驱动程序特定的选项
				*/
				$stmt->bindParam(":id", $id, PDO::PARAM_INT); 
			}

			
		}
	}
}
?>