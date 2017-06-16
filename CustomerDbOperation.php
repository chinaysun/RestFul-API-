<?php

/**
 * Created by Shawn
 * Date:24/05/2017
 * Basic Operation 
 */
Class CustomerDbOperation
{
	private $conn;

	function __construct()
	{
		//connect to db when initial
		require_once dirname(__FILE__) . '/Constants.php';
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->createConnect();



	}

	function __destruct()
	{
		$this->conn->close();
	}

	public function createUser($Ph_number,$Email,$Password)
	{

		//CHEKC EXIT FIRST
		if (!$this->checkUserExist($Ph_number)) 
		{
			//encry the password for secure
			$encryPassword = password_hash($Password,PASSWORD_DEFAULT);

			//INSERT IF NOT EXIST
			$stmt = $this->conn->prepare("INSERT INTO customer(Ph_number,Email,PasswordHash) VALUES (?,?,?)");
			$stmt->bind_param("sss",$Ph_number,$Email,$encryPassword);

			if($stmt->execute())
			{
				return USER_CREATED;

			}else
			{
				return SQL_EXECUTE_ERROR;
			}
			
		}else
		{
			return USER_ALREADY_EXIST;
		}
	}

	//Login function
	public function userLogin($Ph_number,$usersPassword)
	{
		if ($this->checkUserExist($Ph_number)) 
		{
			$stmt = $this->conn->prepare("SELECT PasswordHash FROM customer WHERE Ph_number = ? LIMIT 1");
			$stmt->bind_param("s",$Ph_number);

			if ($stmt->execute()) 
			{
				//store result
				$result = $stmt->get_result();
				$storePassword = $result->fetch_array(MYSQLI_ASSOC);
				$compareResult = password_verify($usersPassword,$storePassword["PasswordHash"]);

				if ($compareResult) 
				{
					return PASSWORD_CORRECT;
				}else
				{
					return PASSWORD_INCORRECT;
				}

			}else
			{
				return SQL_EXECUTE_ERROR;
			}

		}else
		{
			return USER_DOES_NOT_EXIST;
		}
	}

	//get user info method
	public function getUserInfo($Ph_number)
	{
		if($this->checkUserExist($Ph_number))
		{
			$stmt = $this->conn->prepare("SELECT Email,FirstName,LastName,Image FROM customer WHERE Ph_number = ? LIMIT 1");
			$stmt->bind_param("s",$Ph_number);

			if ($stmt->execute()) 
			{
				$result = $stmt->get_result();
				$returnResult = $result->fetch_array(MYSQLI_ASSOC);

				//Why below code does not work
				// foreach ($returnResult as $value) {
				// 	if (!isset($value)) {
				// 		$value = "Empty";
				// 	}
				// }

				//deal with NULL in the databse
				foreach ($returnResult as $key => $value) {
					if (!isset($returnResult[$key])) {
						$returnResult[$key] = "";
					}
				}

				

				return $returnResult;


			}else
			{
				return SQL_EXECUTE_ERROR;
			}

		}else
		{
			return USER_DOES_NOT_EXIST;
		}

	}

	//update userInfo Function
	public function updateUserInfo($Ph_number,$UserNewInfo,$UserInfoType)
	{
		if ($this->checkUserExist($Ph_number)) 
		{
			$sql = "UPDATE customer SET ".$UserInfoType."='".$UserNewInfo."' WHERE Ph_number='".$Ph_number."'";

			if ($this->conn->query($sql))
			{
				return UPDATE_USERINFO_SUCCESSFULLY;
			}
			else
			{
				return SQL_EXECUTE_ERROR;
			}

		}
		else
		{
			return USER_DOES_NOT_EXIST;
		}

	}

	private function checkUserExist($phone)
	{
		$stmt = $this->conn->prepare("SELECT id FROM customer WHERE Ph_number = ? ");
		$stmt->bind_param("s",$phone);
		$stmt->execute();
		$stmt->store_result();

		return $stmt->num_rows > 0;

	}

}
?>