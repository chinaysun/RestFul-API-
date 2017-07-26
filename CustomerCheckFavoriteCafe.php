<?php

/**
 * Created by Shawn
 * Date:29/06/2017
 * Customer Check Favorite Cafe API
 */

$response = array();
require_once("CustomerDbOperation.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	$request_params = $_REQUEST;

	if (!verifyRequiredParams(array(Ph_number,ShopID),$request_params)) 
	{
		$Ph_number = $_POST['Ph_number'];
		$ShopID = $_POST['ShopID'];

		$db = new CustomerDbOperation();

		$result = $db->checkFavoriteCafe($Ph_number,$ShopID);

		if ($result == NO_RESULT) 
		{
		
			$response['error'] = false;
			$response['message'] = 0;

		}elseif($result == SQL_EXECUTE_ERROR)
		{
			$response['error'] = true;
			$response['message'] = "Some Error Occur";

		}else
		{

			$response['error'] = false;
			$response['message'] = 1;
		}

	}else
	{
		$response['error'] = true;
    	$response['message'] = 'Missed Required Information';
	}

	

}else
{
	$response['error'] = true;
	$response['message'] = 'Invalid Request';
}

function verifyRequiredParams($required_field,$request_params)
{
	//Getting the request parameters from $_REQUEST becuase it is treated as not modify
	//$request_params = $_REQUEST;

	foreach ($required_field as $field) 
	{
		//check if a para is missing two situation one is unset and other is not vaule
	 	if(!isset($request_params[$field]) || strlen(trim($request_params[$field])) <=0)
	 	{
	 		//true means some params missing or unset
	 		return true;
	 	}
	}
    
    //false means not params missing
	return false; 
}

echo json_encode($response);

?>