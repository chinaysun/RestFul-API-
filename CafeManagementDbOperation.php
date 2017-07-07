<?php

/**
 * Created by Shawn
 * Date:18/06/2017
 * Basic Db Operation For Cafe Management
 */

class CafeManagementDbOperation
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

	public function getCafeGeoLocationList()
	{

		$sql = "SELECT a.ShopID,a.Latitude,a.Longitude,b.Name,b.Star FROM cafeGeoLocation AS a JOIN cafe AS b ON a.ShopID = b.ShopID";

		$result = $this->conn->query($sql);


		if($result->num_rows > 0)
		{
			$returnArray = array();
			$numberOfRow = 0;

			while($row = $result->fetch_assoc())
			{
				$returnArray[$numberOfRow]["ShopID"] = $row["ShopID"];
				$returnArray[$numberOfRow]["Latitude"] = $row["Latitude"];
				$returnArray[$numberOfRow]["Longitude"] = $row["Longitude"];
				$returnArray[$numberOfRow]["Name"] = $row["Name"];
				$returnArray[$numberOfRow]["Star"] = $row["Star"];
				$numberOfRow += 1;
			}

			return $returnArray;

		}else
		{
			return NO_RESULT;
		}


	}

	//get cafe info method
	public function getCafeInfo($ShopID)
	{
		if($this->checkCafeExist($ShopID))
		{
			$stmt = $this->conn->prepare("SELECT ABN,Description,Image,Ph_number FROM cafe WHERE ShopID = ? LIMIT 1");
			$stmt->bind_param("s",$ShopID);

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

	//get products
	public function checkAvailableProductList($ShopID)
	{

		if($this->checkCafeExist($ShopID))
		{
			$stmt = $this->conn->prepare("SELECT Coffee,Dessert FROM cafeProduct WHERE ShopID = ?");
			$stmt->bind_param("s",$ShopID);

			if($stmt->execute())
			{
				$result = $stmt->get_result();
				$result = $result->fetch_array(MYSQLI_ASSOC);


				$returnResult = array();

				foreach ($result as $key => $value) 
				{

					if ($value)
					{
						$sql = "SELECT ProductID FROM ".strtolower($key)." WHERE ShopID = ".$ShopID;
						
						$sqlResult = $this->conn->query($sql);

						if($sqlResult)
						{
							$returnResult[strtolower($key)] = $sqlResult->num_rows;

						}else
						{
							return SQL_EXECUTE_ERROR;

						}


					}else
					{
						$returnResult[strtolower($key)] = 0;

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

	private function checkCafeExist($ShopID)
	{
		$stmt = $this->conn->prepare("SELECT ShopID FROM cafe WHERE ShopID = ? ");
		$stmt->bind_param("s",$ShopID);
		$stmt->execute();
		$stmt->store_result();

		return $stmt->num_rows > 0;

	}


}



?>