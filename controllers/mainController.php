<?php

//Get the Database Connection Parameters
require 'config/database.php';

//**********Demographics Models*****************//
require 'model/patientModel.php';
require 'model/patient_identifierModel.php';
require 'model/personModel.php';
require 'model/person_nameModel.php';
require 'model/person_addressModel.php';

//**********Clinicals Models*******************//
require 'model/obsModel.php';
require 'model/encounterModel.php';
require 'model/visitModel.php';

//**********Dictionaries*********************//
require 'dictionaries/clinicalDictionary.php';

// This is the main class that will be used to do the migration proper.
// Several methods will be created here inorder to perform the migration.
class seedcareToNMRS {
		private $facilityName = "";
		private $datimId = "";
		public $conn;
		public $identifier;
	
		/*
		function __construct( $facilityName, $datimId ) {
			$this->facilityName = $facilityName;
			$this->datimId = $datimId;
		}
		*/

	// Validate Inputs Function
	public function validateData($data){		
		$data = trim($data);
		$data = stripslashes($data);
		// $data = real_escape_string($conn,$data);
		$data = htmlspecialchars($data);
		return $data;
	}

	// Convert string to date
	public function nmrsDateTime($datestring){
		return date("Y-m-d H:i:s", strtotime($datestring));
	}

	public function getObsGroupID($obsrow){
		return NULL;
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
					
					/* Open up the file
					$file = fopen($fileName, "r");
					
					$getfile = file($fileName);
					$rows = count($getfile);
					
					*/
					switch($_POST['data_category']){

						case 'Demographics':
							// List of Tables to update with data from the demographics CSV
						
							$demographicsTables = array('patient','person','person_name','person_address','patient_identifier');
								
							foreach ($demographicsTables as $key => $dtable) {
									$all_values = "";
									$row = 1;

									// Open up the file
									$file = fopen($fileName, "r");
									
									$getfile = file($fileName);
									$rows = count($getfile);

									// Loop throught the Uploaded CSV File
									while (($csvColumn = fgetcsv($file, 10000, ",")) !== FALSE) {
										
										$currentMigration = "Migrating ".$dtable." :". $csvColumn[0]."<hr>";
										// Count all the rows
										
										

										// Escape / Ignore the first row becuase it contains headings 
										// and we need the headings to be there so that the column count won't throw error
										if($row == 1){ $row++; continue; }

										// Log progress in the console and on screen;
										// echo("<script>console.log('PHP: " . $currentMigration . "');</script>");
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
										

											// Truncate and remove the contents of the existing tables
											mysqli_query($conn,"TRUNCATE $dtable") or die(mysqli_error($conn));

											
											if($dtable=='patient_identifier'){
												$identifierList = array(3,4,5,6);
												$count_id = count($identifierList);
												$r = 1;
												foreach($identifierList as $identifier){

													$nmrs_fields = 'nmrs'.$dtable.'Fields';
													$seedcare_fields = 'seedcare'.$dtable.'Fields';

													// Get Columns from the arrays stored in each functions
													$columns = implode(", ",call_user_func($nmrs_fields));

													//$escaped_values = implode(',', (seedcareFields($csvColumn)));
													$values  = implode(",", call_user_func($seedcare_fields,$csvColumn,$identifier));
													if(($row*$r)<($rows*$count_id)){
														// echo $row." - Patient No:".$csvColumn[0]."<br>";

														$all_values.= "(".$values."),";
													}else{
							
														// If the Last row is reach then write the sql
														$all_values.= "(".$values.")";
														$demographicsSQL = "INSERT INTO `$dtable` ($columns) VALUES $all_values ON DUPLICATE KEY UPDATE voided=voided";
							
														// Execute the MySQLI Query
														$result = mysqli_query($conn, $demographicsSQL) or die(mysqli_error($conn));
													}

													$r++;
													
												}
											}else{

											
												$nmrs_fields = 'nmrs'.$dtable.'Fields';
												$seedcare_fields = 'seedcare'.$dtable.'Fields';

												// Get Columns from the arrays stored in each functions
												$columns = implode(", ",call_user_func($nmrs_fields));

												//$escaped_values = implode(',', (seedcareFields($csvColumn)));
												$values  = implode(",", call_user_func($seedcare_fields,$csvColumn));

												if($row<$rows){
													$all_values.= "(".$values."),";
												}else{
						
													// If the Last row is reach then write the sql
													$all_values.= "(".$values.")";
													$demographicsSQL = "INSERT INTO `$dtable` ($columns) VALUES $all_values ON DUPLICATE KEY UPDATE voided=voided";
						
													// Execute the MySQLI Query
													$result = mysqli_query($conn, $demographicsSQL) or die(mysqli_error($conn));
												}

												// Increment row count
											}
										$row++;
										$currentMigration="";
											
										}
																			
										
										// Check if we have reached the last row (because it helps to right just one insert query)
										// if not continue building up data to be uploaded once (helps to optimise)
										
										
										if (!empty($result)) {
											echo "<div class='alert alert-success'> $dtable's CSV Data has been Imported into the Database</div>";											
										} else {
											echo "<div class='alert alert-danger'>Problem in Importing CSV Data</div>";
										}

										
									}
							
							
						break;

						case 'Clinicals':
							$clinicalTables = array('visit','encounter','obs');
								
							foreach ($clinicalTables as $key => $cltable) {
									//Load the Clinical CSV Data
									$clinicalCSV = array_map('str_getcsv', file('assets/resources/clinicals.csv'));

									// List all the columns that will be used to generate obs data according to the CSV Uploaded

									$obsColumnNos = array(3,4,5,6,7,8,9,10,11,14,15,16,17,18,19,20,21,22,23,24,25);
									$countObsFields = count($obsColumnNos);

									$all_values = "";
									$row = 1;
									
									// Open up the file
									$file = fopen($fileName, "r");
									
									$getfile = file($fileName);
									$rows = count($getfile);

									echo $cltable;
									// Loop through the Uploaded CSV File
									while (($csvColumn = fgetcsv($file, 10000, ",")) !== FALSE) {
										
										$currentMigration = "Migrating ".$cltable." :". $csvColumn[0]."<hr>";
										// Count all the rows
										
										

										// Escape / Ignore the first row becuase it contains headings 
										// and we need the headings to be there so that the column count won't throw error
										if($row == 1){ $row++; continue; }

										// Log progress in the console and on screen;
										// echo("<script>console.log('PHP: " . $currentMigration . "');</script>");
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
										

											// Truncate and remove the contents of the existing tables
											// mysqli_query($conn,"TRUNCATE $dtable") or die(mysqli_error($conn));

											
											if($cltable=='obs'){
												// Get the OBS Columns from the obsModel.php
												
												$ocount = 1;
												$obsvalNumeric = "";
												$obsvalCoded = "";
												$obsvalDateTime = "";
												$obsvalOthers = "";

												foreach($obsColumnNos as $obsrow){
													$columns = $obscolumns.",".obsValueType($csvColumn,$obsrow);
													//Check if OBS Row is NULL
													if($csvColumn[$obsrow]=="NULL" || $csvColumn[$obsrow]=="" ){
														$ocount++;
														continue;
													}else{
														$values="'".$csvColumn[0]."',"
														."'".getCID($obsrow)."',"
														."'".$csvColumn[2]."',"
														."'".$csvColumn[2]."',"
														."'".nmrsDateTime($csvColumn[12])."',"
														."'".$csvColumn[1]."',"
														."'".getObsGroupID($obsrow)."',"
														."'".$csvColumn[2]."',"
														."'".getAns($obsrow,$csvColumn[$obsrow])."',"
														."'".$csvColumn[11]."',"
														."'".nmrsDateTime($csvColumn[12])."',0,"
														."'".bin2hex(random_bytes(6))."'";

														if($row<$rows && $ocount<$countObsFields){
															// $all_values.="(".$values."),";

															switch (obsValueType($csvColumn,$obsrow)){
																case "value_numeric":																	
																	$obsvalNumeric.="(".$values."),";
																	break;

																case "value_coded":																	
																	$obsvalCoded.="(".$values."),";
																	break;

																case "value_datetime":																	
																	$obsvalDateTime.="(".$values."),";
																	break;

																default:																	
																	$obsvalOthers.="(".$values."),";
																	break;
															}
															
														}else{
								
															// If the Last row is reach then write the sql
															// $all_values.= "(".$values.")";

															switch (obsValueType($csvColumn,$obsrow)){
																case "value_numeric":																	
																	$obsvalNumeric.="(".$values.")";
																	break;

																case "value_coded":																	
																	$obsvalCoded.="(".$values.")";
																	break;

																case "value_datetime":																	
																	$obsvalDateTime.="(".$values.")";
																	break;

																default:																	
																	$obsvalOthers.="(".$values.")";
																	break;
															}															
															
															echo $obsSQL1 = "INSERT INTO `$cltable`($columns,value_numeric) VALUES $obsvalNumeric ON DUPLICATE KEY UPDATE voided=voided";
															echo $obsSQL2 = "INSERT INTO `$cltable`($columns,value_coded) VALUES $obsvalCoded ON DUPLICATE KEY UPDATE voided=voided";
															echo $obsSQL3 = "INSERT INTO `$cltable`($columns,value_datetime) VALUES $obsvalDateTime ON DUPLICATE KEY UPDATE voided=voided";
															echo $obsSQL4 = "INSERT INTO `$cltable`($columns,value_text) VALUES $obsvalOthers ON DUPLICATE KEY UPDATE voided=voided";
																							
															// Execute the MySQLI Query
															$result1 = mysqli_query($conn, $obsSQL1) or die(mysqli_error($conn));
															$result2 = mysqli_query($conn, $obsSQL2) or die(mysqli_error($conn));
															$result3 = mysqli_query($conn, $obsSQL3) or die(mysqli_error($conn));
															$result4 = mysqli_query($conn, $obsSQL4) or die(mysqli_error($conn));
														}
														
														$ocount++;

													}				
												}

												$row++;
												$currentMigration="";

											}else{

											
												$nmrs_fields = 'nmrs'.$cltable.'Fields';
												$seedcare_fields = 'seedcare'.$cltable.'Fields';

												// Get Columns from the arrays stored in each functions
												$columns = implode(", ",call_user_func($nmrs_fields));

												//$escaped_values = implode(',', (seedcareFields($csvColumn)));
												$values  = implode(",", call_user_func($seedcare_fields,$csvColumn));

												if($row<$rows){
													$all_values.= "(".$values."),";
												}else{
						
													// If the Last row is reach then write the sql
													$all_values.= "(".$values.")";
													echo $clinicalsSQL = "INSERT INTO `$cltable`($columns) VALUES $all_values ON DUPLICATE KEY UPDATE voided=voided";
						
													// Execute the MySQLI Query
													$result = mysqli_query($conn, $clinicalsSQL) or die(mysqli_error($conn));
												}

												// Increment row count
											}
										$row++;
										$currentMigration="";
											
										}
																			
										
										// Check if we have reached the last row (because it helps to right just one insert query)
										// if not continue building up data to be uploaded once (helps to optimise)
										
										
										if (!empty($result)) {
											echo "<div class='success'> $cltable's CSV Data has been Imported into the Database</div>";
										} else {
											echo "<div class='success'>Problem in Importing CSV Data</div>";
										}

										
									}
							
							
						break;

						case 'Users':
						break;

						case 'Lab':
						break;

						case 'All':
						break;

						default:
						echo "Please select a data category from the list";
						break; 

					}
						
					
					// Reactivate the Foreign_Key Checks so that table can be related properly
					mysqli_query($conn,"SET FOREIGN_KEY_CHECKS = 1");

			
				}
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