<?php

/**
 * Created by Shawn
 * Date:27/06/2017
 * Customer Updated Favorite Cafe API
 * Insert - Only one cafe will be inserted
 * Delete - One or more cafe will be deleted
 */

$response = array();
require_once("CustomerDbOperation.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

	$request_params = $_REQUEST;

	if (!verifyRequiredParams(array(Ph_number,UpdateInfo,Operation),$request_params)) 
	{

		$Ph_number = $_POST['Ph_number'];
		$Operation = $_POST['Operation'];
		$UpdateInfo = $_POST['UpdateInfo'];

		//connect to db
		$db = new CustomerDbOperation();
		$result = UPDATE_USERINFO_SUCCESSFULLY;

		if ($Operation == 'Delete')
		{
			//loop to delete info
			foreach ($UpdateInfo as $key => $value) 
			{

				 $result = $db->deleteFavoriteCafe($Ph_number,$value);

				 if($result == 'SQL_EXECUTE_ERROR')
				 {
				 	break;
				 }

			}



		}elseif ($Operation == 'Insert') {
			
			foreach ($UpdateInfo as $key => $value) 
			{

				 $result = $db->insertFavoriteCafe($Ph_number,$value);

				 if($result == 'SQL_EXECUTE_ERROR')
				 {
				 	break;
				 }

			}
		}

		if($result == UPDATE_USERINFO_SUCCESSFULLY)
		{
			$response['error'] = false;
    		$response['message'] = 'Update Successfully';

		}else
		{
			$response['error'] = true;
    		$response['message'] = 'Some Error Occur';

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
	 	if(!isset($request_params[$field]))
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