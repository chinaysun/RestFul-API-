<?php

/**
 * Created by Shawn
 * Date:18/06/2017
 * Provides Clients with Cafe Management API
 */

$response = array();
require_once("CafeManagementDbOperation.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') 
{
	
	$db = new CafeManagementDbOperation();

	$result = $db->getCafeGeoLocationList();

	if ($result == NO_RESULT) 
	{
		
		$response['error'] = true;
		$response['message'] = 'Some Error Occur';

	}else
	{

		$response = $result;
		$response['error'] = false;
	}

}else
{
	$response['error'] = true;
	$response['message'] = 'Invalid Request';
}

echo json_encode($response);

?>