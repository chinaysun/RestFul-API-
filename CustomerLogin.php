<?php
/**
 * Created by Shawn
 * Date:28/05/2017
 * Customer Login Function
 */

//store result
$response = array();
require_once('CustomerDbOperation.php');


// LOGIN METHOD USED POST as well, because it is much secure
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	
	$request_params = $_REQUEST;



	if (!verifyRequiredParams(array(Ph_number,Password),$request_params)) {

			$Ph_number = $_POST['Ph_number'];
	        $Password = $_POST['Password'];

	        $db = new CustomerDbOperation();

	        $result = $db->userLogin($Ph_number,$Password);

	    //making the response accordingly
        	if ($result == PASSWORD_CORRECT) {
            	$response['error'] = false;
            	$response['message'] = 'Password Correct';
        	} elseif ($result == PASSWORD_INCORRECT) {
            	$response['error'] = true;
            	$response['message'] = 'Password Incorrect';
        	} elseif ($result == SQL_EXECUTE_ERROR) {
            	$response['error'] = true;
            	$response['message'] = 'Some Error Occurred';
        	} elseif ($result == USER_DOES_NOT_EXIST) {
            	$response['error'] = true;
            	$response['message'] = 'User Does Not Exist';
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