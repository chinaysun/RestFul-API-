<?php

/**
 * Created by Shawn
 * Date:25/07/2017
 * Get Order History List
 */

$response = array();
require_once("CustomerDbOperation.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	$request_params = $_REQUEST;

	if (!verifyRequiredParams(array(CustomerID),$request_params)) 
	{
		$CustomerID = $_POST['CustomerID'];

		$db = new CustomerDbOperation();

		$result = $db->getUserOrderHistoryList($CustomerID);

		if ($result == NO_RESULT) 
		{
		
			$response['error'] = true;
			$response['message'] = "";

		}elseif($result == SQL_EXECUTE_ERROR)
		{
			$response['error'] = true;
			$response['message'] = "Some Error Occur";

		}else 
		{

			$response['error'] = false;
			$response['message'] = $result;
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