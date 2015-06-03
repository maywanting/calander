<?php

class admin extends db_connect
{
  	private $_saltLength = 7; //盐的长度

  	public function __construct($db=NULL, $saltLength=NULL)
  	{
  		parent::__construct($db);

  		if(is_int($saltLength))
  		{
  			$this->_saltLength = $saltLength;
  		}
  	}

  	public function processLoginForm()
  	{
  		if ($_POST['action'] != 'user_login'){
  			return "Invalid action supplied for processLoginForm.";
  		}
  		$uname = htmlentities($_POST['username'], ENT_QUOTES);
  		$pword = htmlentities($_POST['password'], ENT_QUOTES);

  		$sql = "SELECT `user_id`, `user_name`, `user_email`, `user_pass`
  				FROM `users`
  				WHERE 
  					`user_name` = :uname
  				LIMIT 1";
  		try{
  			$stmt = $this->db->prepare($sql);
  			$stmt->bindParam(':uname', $uname, PDO::PARAM_STR);

  			if (!$stmt->execute()){
  				echo 111;
  				$this->_errorOutPut($stmt->errorInfo());
  			}

  			//mixed array_shift ( array &$array ) 删除数组中头一个，并返回删除的元素，所有的数字键名将改为从零开始计数，文字键名将不变。
  			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  			$user = array_shift($result);
  			$stmt->closeCursor();

  		}catch (Exception $e){
  			die ($e->getMessage());
  		}

  		if (isset($user) == false){
  			return "No user found with that ID";
  		}

  		$hash = $this->_getSaltedHash($pword, $user['user_pass']);

  		if ($user['user_pass'] == $hash){
  			$_SESSION['user'] = array(
  					'id' => $user['user_id'],
  					'name' => $user['user_name'],
  					'email' => $user['user_email']
  				);
  			return true;
  		}else{
  			return "Your username or password is invalid";
  		}
  	}

  	public function processLogout()
  	{
  		if ($_POST['action'] != 'user_logout')
  		{
  			return "Invalid action supplied for processLogout";
  		}

  		session_destroy();
  		return TRUE;
  	}

  	//密码加“盐”处理
  	private function _getSaltedHash($string, $salt = NULL)
  	{
  		if ($salt == NUL)
  		{
  			$salt = substr(md5(time()), 0, $this->_saltLength);
  		}
  		else
  		{
  			$salt = substr($salt, 0, $this->_saltLength);
  		}
  		return $salt . sha1($salt . $string);
  	}
}
?>