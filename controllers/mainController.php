<?php

require '../config/database.php';
//get connection parameters
// require 'config/database.php';

// This is the main class that will be used to do the migration proper.
// Several methods will be created here inorder to perform the migration
class seedcareToNMRS {
		private $facilityName = "";
		private $datimId = "";
	
		function __construct( $facilityName, $datimId ) {
			$this->facilityName = $facilityName;
			$this->datimId = $datimId;
		}

	//Validate Inputs Function
	function validateData($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = mysql_real_escape_string($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	function checkConnection(){

		if(isset($_POST['connect'])){

			$servername = validateData($_POST['servername']);
			$username = validateData($_POST['username']);
			$password = validateData($_POST['password']);
			$db = validateData($_POST['database']);
			$port_no = validateData($_POST['port_no']);
			

			$conn = connectDB($servername,$username,$password,$db,$port_no);
     
			if($conn){
				echo "<input type='hidden' name='servername' value='.$servername.'>";
				echo "<input type='hidden' name='username' value='.$username.'>";
				echo "<input type='hidden' name='password' value='.$password.'>";
				echo "<input type='hidden' name='db' value='.$db.'>";
				echo "<input type='hidden' name='port_no' value='.$port_no.'>";

				echo "<h3 class='alert alert-success' style='text-align: center;'>You are Connected to the Database</h3>";	
			}else{
				echo "<h3 class='alert alert-danger'>Error In Connecting to NMRS Database <hr> Please check the connection parameters and try again</h3>";
			}
			
		}
		
	}

	/*
	if (isset($_POST["import"])) {
		
		$fileName = $_FILES["file"]["tmp_name"];
		
		if ($_FILES["file"]["size"] > 0) {
			
			$file = fopen($fileName, "r");
			
			while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
				$sqlInsert = "INSERT into users (userId,userName,password,firstName,lastName)
					values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "')";
				$result = mysqli_query($conn, $sqlInsert);
				
				if (! empty($result)) {
					$type = "success";
					$message = "CSV Data Imported into the Database";
				} else {
					$type = "error";
					$message = "Problem in Importing CSV Data";
				}
			}
		}
	}
	*/
 
}