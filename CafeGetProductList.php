<?php
/**
 * Created by Shawn
 * Date:05/07/2017
 * Get Available Product List API
 */


$response = array();
require_once('CafeManagementDbOperation.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	
	$request_params = $_REQUEST;

	if (!verifyRequiredParams(array(ShopID),$request_params)) 
	{

		$ShopID = $_POST['ShopID'];

	    $db = new CafeManagementDbOperation();

	    $result = $db->checkAvailableProductList($ShopID);

	   	//making the response accordingly
        if ($result == SQL_EXECUTE_ERROR) 
        {
            $response['error'] = true;
            $response['message'] = 'Some Error Occurred';

        } elseif ($result == USER_DOES_NOT_EXIST) 
        {
            $response['error'] = true;
            $response['message'] = 'User Does Not Exist';
        	
        }else
        {	
        	$response['message'] = $result;
        	$response['error'] = false;
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