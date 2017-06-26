<?php
/**
 * Created by Shawn
 * Date:06/06/2017
 * Allow Customer upload their profile Image
 */

$response = array();
require_once('CustomerDbOperation.php');
require_once("Constants.php");

//check the request method
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	//create the target dir & Get Type
	$target_file = CUSTOMER_PROFILE_IMAGE_FILE . basename($_FILES['profileImageToUpload']['name']);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

	//controller 
	$allowUpload = true;

	//check parameters
	if (!isset($_POST['Ph_number']) || strlen(trim($_POST['Ph_number'])) <= 0 ) 
	{
		$allowUpload = false;
		$response['error'] = true;
		$response['message'] = "Missed Required Information";
	}

	//check if image file is an actual image or fake image
	$check = getimagesize($_FILES['profileImageToUpload']['tmp_name']);

	if ($check == false) 
	{
		$allowUpload = false;
		$response['error'] = true;
		$response['message'] = "File Is Not An Image";
	}

	//check file size
	if ($_FILES['profileImageToUpload']['size'] > 500000) 
	{
		
		$allowUpload = false;
		$response['error'] = true;
		$response['message'] = "Image Is Too Big To Upload";

	}

	//check file format
	if ($imageFileType != 'jpeg' && $imageFileType != "png" ) 
	{
		
		$allowUpload = false;
		$response['error'] = true;
		$response['message'] = "Only JPEG or PNG Are Allowed";
	}

	//upload the image
	if (allowUpload) {
		
		//create the db to store target dir
		$db = new CustomerDbOperation();

		//prepare parameters
		$Ph_number = $_POST['Ph_number'];
		$UserNewInfo = $target_file;
		$UserInfoType = "Image";

		//update the dir in db
		$result = $db->updateUserInfo($Ph_number,$UserNewInfo,$UserInfoType);

	    if ($result == UPDATE_USERINFO_SUCCESSFULLY) 
	    {
	    	//check the file
	    	if (file_exists($target_file)) 
	    	{
	    		//remove the exist file
	    		if (!rmdir($target_file))
	    		{
	    			//Move the image to store
	    			if (move_uploaded_file($_FILES["profileImageToUpload"]["tmp_name"], $target_file)) 
	    			{
        				$response['error'] = false;
            			$response['message'] = 'Profile Image Uploaded Successfully';

    				} else 
    				{
    					$response['error'] = true;
            			$response['message'] = 'Sorry, There Was An Error Uploading Your File';	
    				}

	    		}else
	    		{
    					$response['error'] = true;
            			$response['message'] = 'Sorry, There Was An Error Removing Your Previous File';	
	    		}

	    	}else
	    	{
	    		// move the image to file directly
	    		if (move_uploaded_file($_FILES["profileImageToUpload"]["tmp_name"], $target_file)) 
	    		{
        			$response['error'] = false;
            		$response['message'] = 'Profile Image Uploaded Successfully';

    			} else 
    			{
    				$response['error'] = true;
            		$response['message'] = 'Sorry, There Was An Error Uploading Your File';	
    			}
	    	}
            
        } elseif ($result == USER_DOES_NOT_EXIST) {
            $response['error'] = true;
            $response['message'] = 'User Does Not Exist';
        } elseif ($result == SQL_EXECUTE_ERROR) {
            $response['error'] = true;
            $response['message'] = 'Some Error Occurred';
        }

	}


}else
{
	$response['error'] = true;
	$response['message'] = "Invaild Request";
}


echo json_encode($response);

?>