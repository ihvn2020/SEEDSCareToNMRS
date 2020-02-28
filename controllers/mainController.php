<?php

require 'config/database.php';
require 'model/patientModel.php';
require 'model/patient_identifierModel.php';
require 'model/personModel.php';
require 'model/person_nameModel.php';
require 'model/person_addressModel.php';
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

				echo "<h3 class='alert alert-success' style='text-align: center;'>You are Connected to the <b>$db</b> Database </h3>";	
			}else{
				echo "<h3 class='alert alert-danger'>Error In Connecting to NMRS Database <hr> Please check the connection parameters and try again</h3>";
			}
			
		}
		
	}

	public function uploadCSV()
	{
		$conn = connectDB($_POST['servername'],$_POST['username'],$_POST['password'],$_POST['db']);
			// CSV File Upload
			// Check Form Sumbit?
			if(isset($_POST["MigrateData"])){

				// Get the temporary file name
				$fileName = $_FILES["file"]["tmp_name"]; 

				// Disable foreign key and dependency/relationship checks
				mysqli_query($conn,"SET FOREIGN_KEY_CHECKS = 0");

					
				// Ensure that the File is uploaded
				if ($_FILES["file"]["size"] > 0) {
					
					// Open up the file
					$file = fopen($fileName, "r");
					
					$row = 1;
					
					

					// Loop throught the Uploaded CSV File
					while (($csvColumn = fgetcsv($file, 10000, ",")) !== FALSE) {
						$all_values = "";
						$currentMigration = "Migrating Patient: ". $csvColumn[0]."<hr>";
						// Count all the rows
						$num = count($csvColumn);

						// Escape / Ignore the first row becuase it contains headings 
						// and we need the headings to be there so that the column count won't throw error
						if($row == 1){ $row++; continue; }

						// Log progress in the console and on screen;
						echo("<script>console.log('PHP: " . $currentMigration . "');</script>");
						/*
						while (true) {
							// Echo an extra line, and flush the buffers
							// to ensure it gets displayed.
							echo ' .';
							flush();
							ob_flush();
						  
							// Now sleep for 1 second and check again (Not used yet):
								// Progress Bar Code may be changed to Javascript
							// sleep(1);
						  }
						  */
						  
						  // List of Tables to update with data from the demographics CSV
						  $demographicsTables = array('patient','person','person_name','person_address');
						
						  foreach ($demographicsTables as $key => $dtable) {

							// Truncate and remove the contents of the existing tables
							mysqli_query($conn,"TRUNCATE $dtable") or die(mysqli_error($conn));

							if($dtable=='patient_identifier'){
								$identifierList = array($csvColumn[39]=>3,$csvColumn[13]=>5,$csvColumn[11]=>6,$csvColumn[3]=>7,$csvColumn[36]=>8,$csvColumn[20]=>11);
								
								foreach($identifierList as $identifier){

									$nmrs_fields = 'nmrs'.$dtable.'Fields';
									$seedcare_fields = 'seedcare'.$dtable.'Fields';

									// Get Columns from the arrays stored in each functions
									$columns = implode(", ",call_user_func($nmrs_fields));

									//$escaped_values = implode(',', (seedcareFields($csvColumn)));
									$values  = implode(",", call_user_func($seedcare_fields,$csvColumn));
									if($row<$num){
										$all_values.= "(".$values."),";
									}else{
			
										// If the Last row is reach then write the sql
										$all_values.= "(".$values.")";
										$demographicsSQL = "INSERT INTO `$dtable`($columns) VALUES $all_values ON DUPLICATE KEY UPDATE voided=voided";
			
										// Execute the MySQLI Query
										$result = mysqli_query($conn, $demographicsSQL) or die(mysqli_error($conn));
									}
									
								}
							}else{

							
								$nmrs_fields = 'nmrs'.$dtable.'Fields';
								$seedcare_fields = 'seedcare'.$dtable.'Fields';

								// Get Columns from the arrays stored in each functions
								$columns = implode(", ",call_user_func($nmrs_fields));

								//$escaped_values = implode(',', (seedcareFields($csvColumn)));
								$values  = implode(",", call_user_func($seedcare_fields,$csvColumn));

								if($row<$num){
									$all_values.= "(".$values."),";
								}else{
		
									// If the Last row is reach then write the sql
									$all_values.= "(".$values.")";
									$demographicsSQL = "INSERT INTO `$dtable`($columns) VALUES $all_values ON DUPLICATE KEY UPDATE voided=voided";
		
									// Execute the MySQLI Query
									$result = mysqli_query($conn, $demographicsSQL) or die(mysqli_error($conn));
								}
							}
						  }
						

									
						
						// Check if we have reached the last row (because it helps to right just one insert query)
						// if not continue building up data to be uploaded once (helps to optimise)
						
						
						if (! empty($result)) {
							echo "<div class='success'>Patients\' CSV Data Imported into the Database</div>";
						} else {
							echo "<div class='success'>Problem in Importing CSV Data</div>";
						}

						// Increment row count
						$row++;
						$currentMigration="";
					}
					
				}
				
				// Reactivate the Foreign_Ket Checks so that table can be related properly
				mysqli_query($conn,"SET FOREIGN_KEY_CHECKS = 1");

			}
		
		
	}
	
 
}

/*
$query = <<<eof
    LOAD DATA INFILE '$fileName'
     INTO TABLE tableName
     FIELDS TERMINATED BY '|' OPTIONALLY ENCLOSED BY '"'
     LINES TERMINATED BY '\n'
    (field1,field2,field3,etc)
eof;

$db->query($query);
*/
?>