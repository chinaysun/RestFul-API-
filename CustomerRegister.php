<?php
/**
 * Created by Shawn
 * Date:24/05/2017
 * Customer Register Function
 */

//this var will be returned to client
$response = array();
require_once('CustomerDbOperation.php');

//Only deal with Post Method, Otherwise it is a invalid method
if($_SERVER['REQUEST_METHOD']=='POST')
{

	//Getting the request parameters from $_REQUEST becuase it is treated as not modify
	//this was moved out of method because the verify method  does not interface with app
	$request_params = $_REQUEST;

	//check the variables before insert
	if(!verifyRequiredParams(array(Ph_number,Email,Password),$request_params))
	{
		//store all info from post
		$Ph_number   = $_POST['Ph_number'];
		$Email   = $_POST['Email'];
		$Password   = $_POST['Password'];


		//connect to db
		$db = new CustomerDbOperation();

		$result = $db->createUser($Ph_number,$Email,$Password);

		//making the response accordingly
        if ($result == USER_CREATED) {
            $response['error'] = false;
            $response['message'] = 'User Created Successfully';
        } elseif ($result == USER_ALREADY_EXIST) {
            $response['error'] = true;
            $response['message'] = 'User Already Exist';
        } elseif ($result == SQL_EXECUTE_ERROR) {
            $response['error'] = true;
            $response['message'] = 'Some Error Occurred';
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