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
			$sql .= "WHERE `event_id`=:id LIMIT 1";//：id为占位符名，还有一种占位符则是？，替换时用数组下标标识
		}
		else
		{
			$start_ts = mktime(0, 0, 0, $this->_m, 1, $this->_y);
			$end_ts = mktime(23, 59, 59, $this->_m, $this->_daysInMonth, $this->_y);
			$start_date = date('Y-m-d H:i:s', $start_ts);
			$end_date = date('Y-m-d H:i:s', $end_ts);

			$sql .= "WHERE `event_start` BETWEEN '$start_date' AND '$end_date' ORDER BY `event_start`";
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

			$stmt->execute(); //execute([array])方法负责执行准备好的查询，需要替换占位符。一种则是用bindParam函数替换，还有一种则是直接在execute函数中输入array表示
			/*array PDOStatement::fetchAll ([ int $fetch_style [, mixed $fetch_argument [, array $ctor_args = array() ]]] )函数返回一个包含结果集的中所有行的二维数组
				PDO::FETCH_ASSOC：返回一个索引为结果集列名的数组
				PDO::FETCH_BOTH（默认）：返回一个索引为结果集列名和以0开始的列号的数组
				PDO::FETCH_BOUND：返回 TRUE ，并分配结果集中的列值给 PDOStatement::bindColumn() 方法绑定的 PHP 变量。
				PDO::FETCH_CLASS：返回一个请求类的新实例，映射结果集中的列名到类中对应的属性名。如果 fetch_style 包含 PDO::FETCH_CLASSTYPE（例如：PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE），则类名由第一列的值决定
				PDO::FETCH_INTO：更新一个被请求类已存在的实例，映射结果集中的列到类中命名的属性
				PDO::FETCH_LAZY：结合使用 PDO::FETCH_BOTH 和 PDO::FETCH_OBJ，创建供用来访问的对象变量名
				PDO::FETCH_NUM：返回一个索引为以0开始的结果集列号的数组
				PDO::FETCH_OBJ：返回一个属性名对应结果集列名的匿名对象
			*/
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			/*bool PDOStatement::closeCursor ( void )释放到数据库服务的连接，以便发出其他 SQL 语句，但使语句处于一个可以被再次执行的状态。
			等同于一下代码
			do {
    			while ($stmt->fetch());
    			if (!$stmt->nextRowset())	break;
			} while (true);
			*/
			$stmt->closeCursor();
			
			return $results;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	private function _createEventObj()
	{
		$arr = $this->_loadEventData();

		$events = array();
		foreach ($arr as $event) {
			$day = date('j', strtotime($event['event_start']));

			try
			{
				$events[$day][] = new event($event);//原来还可以这么写！！
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}
		return $events;
	}

	public function buildCalendar()
	{
		$cal_month = date('F Y', strtotime($this->_useDate));
		$weekdays = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

		$html = "\n\t<h2>$cal_month</h2>";
		for ($d = 0, $labels = NULL; $d < 7; ++$d)
		{
			$labels .= "\n\t\t<li>" . $weekdays[$d] . "</li>";
		}
		$html .= "\n\t<ul class=\"weekdays\">" . $labels . "\n\t</ul>";

		$events = $this->_createEventObj();

		$html .= "\n\t<ul>";
		for ($i = 1, $c = 1, $t = date('j'), $m = date('m'), $y = date('Y'); $c <= $this->_daysInMonth; $i++)
		{
			//$i记录星期，$c记录日期
			//本月1号开始之前
			$class = ($i <= $this->_startDay) ? "fill" : NULL;

			//标识今天
			if ($c == $t && $m == $this->_m && $y == $this->_y)
			{
				$class = "today";
			}


			$ls = sprintf("\n\t\t<li class=\"%s\">", $class); //sprintf(format,arg1,arg2,arg++)把格式化的字符串写入变量中
			$le = "\n\t\t</li>";

			$event_info = NULL;
			if ($this->_startDay < $i && $this->_daysInMonth >= $c)
			{
				if (isset($events[$c]))
				{
					foreach ($events[$c] as $event) {
						$link = '<a href="view.php?event_id='
							. $event->id . '">' . $event->title
							. '</a>';
						$event_info .= "\n\t\t\t$link";
					}
				}
				$date = sprintf("\n\t\t\t<strong>%02d</strong>", $c++);
			}
			else
			{
				$date = "&nbsp;";
			}

			$wrap = ($i != 0 && $i%7 == 0) ? "\n\t</ul>\n\t<ul>" : NULL;

			$html .= $ls . $date . $event_info . $le . $wrap;
		}

		//本月结束之后空余
		while ($i %7 != 1)
		{
			$html .= "\n\t\t<li class=\"fill\">&nbsp;</li>";
			++$i;
		}

		$html .= "\n\t</ul>\n\n";
		return $html;
	}

	public function test()
	{
		return $this->_createEventObj();
	}
}
?>