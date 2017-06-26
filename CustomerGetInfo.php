<?php
/**
 * Created by Shawn
 * Date:31/05/2017
 * Basic Operation 
 */

$response = array();
require_once('CustomerDbOperation.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	
	$request_params = $_REQUEST;


	if (!verifyRequiredParams(array(Ph_number),$request_params)) {

			$Ph_number = $_POST['Ph_number'];

	        $db = new CustomerDbOperation();

	        $result = $db->getUserInfo($Ph_number);

	    //making the response accordingly
        	if ($result == SQL_EXECUTE_ERROR) {
            	$response['error'] = true;
            	$response['message'] = 'Some Error Occurred';
        	} elseif ($result == USER_DOES_NOT_EXIST) {
            	$response['error'] = true;
            	$response['message'] = 'User Does Not Exist';
        	}else
        	{	
        		$response = $result;
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
