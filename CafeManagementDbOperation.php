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

		$sql = "SELECT ShopID,Latitude,Longitude FROM cafeGeoLocation";

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
				$numberOfRow += 1;
			}

			return $returnArray;

		}else
		{
			return NO_RESULT;
		}


	}


}



?>