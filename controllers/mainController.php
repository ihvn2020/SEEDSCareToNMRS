<?php

require 'config/database.php';
require 'model/patientModel.php';
//get connection parameters
// require 'config/database.php';

// This is the main class that will be used to do the migration proper.
// Several methods will be created here inorder to perform the migration
class seedcareToNMRS {
		private $facilityName = "";
		private $datimId = "";
		public $conn;
	
		/*
		function __construct( $facilityName, $datimId ) {
			$this->facilityName = $facilityName;
			$this->datimId = $datimId;
		}
		*/

	//Validate Inputs Function
	public function validateData($data){
		
		$data = trim($data);
		$data = stripslashes($data);
		// $data = real_escape_string($conn,$data);
		$data = htmlspecialchars($data);
		return $data;
	}

	// This Function checks the user submitted connection parameters and sends the success values to the upload form.
	// Note** This process will be changed to an Ajax call, but in the interim - this works 
	public function checkConnection(){

		if(isset($_POST['connect'])){

			
			$servername = $this->validateData($_POST['servername']);
			$username = $this->validateData($_POST['username']);
			$password = $this->validateData($_POST['password']);
			$db = $this->validateData($_POST['database']);
			$port_no = $this->validateData($_POST['port_no']);
			
			$servername = 'p:'.$servername.':'.$port_no;		
			

			if(connectDB('p:'.$_POST['servername'].':'.$port_no,$_POST['username'],$_POST['password'],$_POST['database'])){
				
				echo "<input type='hidden' name='servername' value='$servername'>";
				echo "<input type='hidden' name='username' value='$username'>";
				echo "<input type='hidden' name='password' value='$password'>";
				echo "<input type='hidden' name='db' value='$db'>";
				echo "<input type='hidden' name='port_no' value='$port_no'>";

				echo "<h3 class='alert alert-success' style='text-align: center;'>You are Connected to the <b>$db</b> Database </h3>";	
			}else{
				echo "<h3 class='alert alert-danger'>Error In Connecting to NMRS Database <hr> Please check the connection parameters and try again</h3>";
			}
			
		}
		
	}

	public function uploadCSV()
	{
		// CSV File Upload
		if(isset($_POST["MigrateData"])){
			
			$fileName = $_FILES["file"]["tmp_name"]; 	
			
			if ($_FILES["file"]["size"] > 0) {
				
				$file = fopen($fileName, "r");
				
				while (($csvColumn = fgetcsv($file, 10000, ",")) !== FALSE) {

					$columns = implode(", ",array_keys(patientFields($csvColumn)));
					$escaped_values = array_map('real_escape_string', array_values(patientFields()));
					$values  = implode(",", $escaped_values);
					$patientSQL = "INSERT INTO `patient`($columns) VALUES ($values)";

					/* $sqlInsert = "INSERT into patient (userId,userName,password,firstName,lastName)
						values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "')";
					*/
						$result = mysqli_query($conn, $patientSQL);
					
					if (! empty($result)) {
						$type = "success";
						$message = "Patients' CSV Data Imported into the Database";
					} else {
						$type = "error";
						$message = "Problem in Importing CSV Data";
					}
				}
			}
		}
		
	}
	
 
}