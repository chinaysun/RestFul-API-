<?php

 // Create connection

Class DbConnect
{
  private $conn;

  function __construct()
   {

   }


 function createConnect()
 {
 	  require_once('Constants.php');
    
    $this->conn = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
    
    if(mysqli_connect_errno())
    {
       echo "Failed to connect to Database: ". mysqli_connect_error();
    }

    return $this->conn;
 
 }

}

?>