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
class seedcareToNMRS extends clinicalDictionary {
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

	public function checkQuery($table,$conn,$vals,$type,$cols){
		if($vals!=""){
			$vals = substr($vals,0,-1);	
			$obsSQL = "INSERT INTO `$table` ($cols,$type) VALUES $vals ON DUPLICATE KEY UPDATE voided=voided";
			// Execute the MySQLI Query
			$result = mysqli_query($conn, $obsSQL) or die(mysqli_error($conn));
		}
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
						
							$demographicsTables = array('patient','person','person_name','person_address','visit','patient_identifier','encounter','obs');
								
							foreach ($demographicsTables as $key => $dtable) {
									$all_values = "";
									$visitArray = array();
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
											// mysqli_query($conn,"TRUNCATE $dtable") or die(mysqli_error($conn));

											
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
											}elseif($dtable=='obs'){
												// $clinicalCSV = array_map('str_getcsv', file('assets/resources/clinicals.csv'));
												// Get all the HIV Enrollment Dates
												// Get the OBS Columns from the obsModel.php												
												
												$obsvalNumeric = "";
												$obsvalCoded = "";
												$obsvalDateTime = "";
												$obsvalText = "";
												$obsvalOthers = "";


													$columns = obscolumns();
													$obsrows = hivEnrollentConcepts($csvColumn);
													$obsrowcount = count($obsrows);
													$obsrowc = 1;
													/*
													"conceptID" => 164947,
													"dataType" => "value_coded",
													"conceptAns" => 164949,
													"csvcol" => ""
													*/

													foreach($obsrows as $obsrow){

														if($obsrow['conceptAns']!=""){
															$answer = $obsrow['conceptAns'];
														}else{
															$answer = $obsrow['csvcol'];
														}

														$values="'".$csvColumn[0]."',"
								 						."'".$obsrow['conceptID']."',"
														."'".$csvColumn[2]."',"
														."'".$csvColumn[2]."',"
														."'".$this->nmrsDateTime($csvColumn[12])."',"
														."'".$csvColumn[1]."',"
														."'".$this->getObsGroupID($obsrowc)."',"
														."'".$csvColumn[2]."',"														
														."'".$csvColumn[11]."',"
														."'".$this->nmrsDateTime($csvColumn[12])."',0,"
														."'".bin2hex(random_bytes(6))."','Care Card Form',"
														."'".$answer."'";

														switch ($obsrow['dataType']){
															case "value_numeric":																	
																$obsvalNumeric.="(".$values."),";
																break;

															case "value_coded":																	
																$obsvalCoded.="(".$values."),";
																break;

															case "value_datetime":																	
																$obsvalDateTime.="(".$values."),";
																break;
															
															case "value_text":																	
																$obsvalText.="(".$values."),";
																break;

															default:																	
																$obsvalOthers.="(".$values."),";
																break;
														}

														// Check the End of OBS Rows

														if(($row*$obsrowc)==($rows*$obsrowcount)){
															$this->checkQuery($dtable,$conn,$obsvalCoded,'value_coded',$columns);
															$this->checkQuery($dtable,$conn,$obsvalNumeric,'value_numeric',$columns);
															$this->checkQuery($dtable,$conn,$obsvalDateTime,'value_datetime',$columns);
															$this->checkQuery($dtable,$conn,$obsvalText,'value_text',$columns);
															$this->checkQuery($dtable,$conn,$obsvalOthers,'value_text',$columns);
														}

														$obsrowc++;									

													
													}
											
											}elseif($dtable=='encounter'){
												$nmrs_fields = 'nmrs'.$dtable.'Fields';
												$seedcare_fields = 'seedcare'.$dtable.'Fields';

												// Get Columns from the arrays stored in each functions
												$columns = implode(", ",call_user_func($nmrs_fields));

												//$escaped_values = implode(',', (seedcareFields($csvColumn)));
												$values  = implode(",", call_user_func($seedcare_fields,$csvColumn,$row));

												if($row<$rows){
													$all_values.= "(".$values."),";
												}else{
						
													// If the Last row is reach then write the sql
													$all_values.= "(".$values.")";
													echo $demographicsSQL = "INSERT INTO `$dtable` ($columns) VALUES $all_values ON DUPLICATE KEY UPDATE voided=voided";
						
													// Execute the MySQLI Query
													$result = mysqli_query($conn, $demographicsSQL) or die(mysqli_error($conn));
												}

												// Increment row count
											}else{
										
												$nmrs_fields = 'nmrs'.$dtable.'Fields';
												$seedcare_fields = 'seedcare'.$dtable.'Fields';

												// To be kept in a text file
												$visitArray.=array($csvColumn[0].','.$csvColumn[12].','.$csvColumn[41]).",";

												// Get Columns from the arrays stored in each functions
												$columns = implode(", ",call_user_func($nmrs_fields));

												//$escaped_values = implode(',', (seedcareFields($csvColumn)));
												$values  = implode(",", call_user_func($seedcare_fields,$csvColumn));

												if($row<$rows){
													$all_values.= "(".$values."),";
												}else{
						
													// If the Last row is reach then write the sql
													$all_values.= "(".$values.")";
													echo $demographicsSQL = "INSERT INTO `$dtable` ($columns) VALUES $all_values ON DUPLICATE KEY UPDATE voided=voided";
						
													// Execute the MySQLI Query
													$result = mysqli_query($conn, $demographicsSQL) or die(mysqli_error($conn));

													// To be kept in a text file (last)
													$visitArray=array_combine($visitArray,array($csvColumn[0].','.$csvColumn[12].','.$csvColumn[41]));
													// serialize your array and store in a Text File
														$serializedData = serialize($visitArray);

														// save serialized data in a text file
														file_put_contents('firstVisit.txt', $serializedData);
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

									$obsColumnNos = array(3,4,5,6,7,8,9,10,14,15,16,17,18,19,20,21,22,23,24,25);
									$countObsFields = count($obsColumnNos);

									$all_values = "";
									$row = 1;
									
									// Open up the file
									$file = fopen($fileName, "r");
									
									$getfile = file($fileName);
									$rows = count($getfile);

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
											// mysqli_query($conn,"TRUNCATE $cltable") or die(mysqli_error($conn));

											
											if($cltable=='obs'){
												// Get the OBS Columns from the obsModel.php
												
												$ocount = 1;
												$obsvalNumeric = "";
												$obsvalCoded = "";
												$obsvalDateTime = "";
												$obsvalOthers = "";
												$obsvalText = "";

												foreach($obsColumnNos as $obsrow){
													// $columns = $obscolumns.",".obsValueType($csvColumn,$obsrow);
													$columns = obscolumns();
													//Check if OBS Row is NULL
													if($csvColumn[$obsrow]=="NULL" || $csvColumn[$obsrow]==""){
														if(($row*$ocount)!=($rows*$countObsFields)){
														$ocount++;
														continue;
													}else{														
														$this->checkQuery($cltable,$conn,$obsvalNumeric,'value_numeric',$columns);
														$this->checkQuery($cltable,$conn,$obsvalCoded,'value_coded',$columns);
														$this->checkQuery($cltable,$conn,$obsvalDateTime,'value_datetime',$columns);
														$this->checkQuery($cltable,$conn,$obsvalOthers,'value_text',$columns);
														}
											
													}else{
														$dictionary = new clinicalDictionary;
														
														$values="'".$csvColumn[0]."',"
								 						."'".$dictionary->getCID($clinicalCSV,$obsrow)."',"
														."'".$csvColumn[2]."',"
														."'".$csvColumn[2]."',"
														."'".$this->nmrsDateTime($csvColumn[12])."',"
														."'".$csvColumn[1]."',"
														."'".$this->getObsGroupID($obsrow)."',"
														."'".$csvColumn[2]."',"														
														."'".$csvColumn[11]."',"
														."'".$this->nmrsDateTime($csvColumn[12])."',0,"
														."'".bin2hex(random_bytes(6))."','Care Card Form',"
														."'".$dictionary->getAns($clinicalCSV,$obsrow,$csvColumn[$obsrow])."'";

														/*  Check Answe Returned
														if($dictionary->getAns($clinicalCSV,$obsrow,$csvColumn[$obsrow])!=''){
															echo "Answer: ". $dictionary->getAns($clinicalCSV,$obsrow,$csvColumn[$obsrow]);
														}
														*/

														if(($row*$ocount)<($rows*$countObsFields)){															

															switch (obsValueType($clinicalCSV,$obsrow)){
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

															switch (obsValueType($clinicalCSV,$obsrow)){
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
															
															echo $obsSQL1 = "INSERT INTO `$cltable` ($columns,value_numeric) VALUES $obsvalNumeric ON DUPLICATE KEY UPDATE voided=voided";
															$obsSQL2 = "INSERT INTO `$cltable` ($columns,value_coded) VALUES $obsvalCoded ON DUPLICATE KEY UPDATE voided=voided";
															$obsSQL3 = "INSERT INTO `$cltable` ($columns,value_datetime) VALUES $obsvalDateTime ON DUPLICATE KEY UPDATE voided=voided";
															$obsSQL4 = "INSERT INTO `$cltable` ($columns,value_text) VALUES $obsvalOthers ON DUPLICATE KEY UPDATE voided=voided";
																							
															// Execute the MySQLI Query
															$result1 = mysqli_query($conn, $obsSQL1) or die(mysqli_error($conn));
															$result2 = mysqli_query($conn, $obsSQL2) or die(mysqli_error($conn));
															$result3 = mysqli_query($conn, $obsSQL3) or die(mysqli_error($conn));
															$result4 = mysqli_query($conn, $obsSQL4) or die(mysqli_error($conn));
															
														}														
														$ocount++;
													}				
												}
											

											}elseif($cltable=='encounter'){
												$nmrs_fields = 'nmrs'.$cltable.'Fields';
												$seedcare_fields = 'seedcare'.$cltable.'Fields';

												// Get Columns from the arrays stored in each functions
												$columns = implode(", ",call_user_func($nmrs_fields));

												//$escaped_values = implode(',', (seedcareFields($csvColumn)));
												$values  = implode(",", call_user_func($seedcare_fields,$csvColumn,$row));

												if($row<$rows){
													$all_values.= "(".$values."),";
												}else{
						
													// If the Last row is reach then write the sql
													$all_values.= "(".$values.")";
													$clinicalsSQL = "INSERT INTO `$cltable`($columns) VALUES $all_values ON DUPLICATE KEY UPDATE voided=voided";
						
													// Execute the MySQLI Query
													$result = mysqli_query($conn, $clinicalsSQL) or die(mysqli_error($conn));
												}
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
													$clinicalsSQL = "INSERT INTO `$cltable`($columns) VALUES $all_values ON DUPLICATE KEY UPDATE voided=voided";
						
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
										echo "<div class='alert alert-success'> $cltable's CSV Data has been Imported into the Database</div>";
									} else {
										echo "<div class='alert alert-danger'>Problem in Importing CSV Data</div>";
									}

										
							}
							
							
						break;

						case 'Pharmacy':

						break;

						case 'Lab':
						break;

						case 'Users':
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