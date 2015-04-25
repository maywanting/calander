<?php

class db_connect
{
	protected $db;

	protected function __construct($dbo = NULL)
	{
		if (is_object($dbo))//bool is_object(mixed $var) 检测变量是否为一个对象
		{ 
			$this->db = $dbo;
		}
		else
		{
			$dbn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
			try
			{
				$this->db = new PDO($dbn, DB_USER, DB_PASS);
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}
	}

	protected function _errorOutPut($error)
	{
		if (DB_ERROR_REPORT == false) return;
		die("ERROR MESSAGE: " . $error[2]);
	}
}
?>