<?php
ini_set('max_executiin_time', 3600);
//Get the default Database Connection Parameters
// Just in case the user forgot and have no either of how to connect
require 'config/database.php';

//**********Demographics Models*****************//
require 'model/patientModel.php';
require 'model/patient_identifierModel.php';
require 'model/personModel.php';
require 'model/person_nameModel.php';
require 'model/person_addressModel.php';
require 'model/patient_programModel.php';

//**********Clinicals Models*******************//
require 'model/obsModel.php';
require 'model/encounterModel.php';
require 'model/visitModel.php';

//***********Users Models*********************//
require 'model/usersModel.php';

//**********Dictionaries**********************//
require 'dictionaries/clinicalDictionary.php';
require 'dictionaries/labDictionary.php';

// This is the main class that will be used to do the migration proper.
// Several methods will be created here inorder to perform the migration.

class seedcareToNMRS extends clinicalDictionary{
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
		return $obsrow;
	}	

	public function checkQuery($table,$conn,$vals,$type,$cols){
		if($vals!=""){			
			// $vals = substr($vals,0,-1);
			$obsSQL = "INSERT INTO `$table` ($cols,$type) VALUES $vals ON DUPLICATE KEY UPDATE voided=voided";
			// Execute the MySQLI Query
			$result = mysqli_query($conn, $obsSQL) or die(mysqli_error($conn));
			if(isset($result)){
				return $result;	
			}
					
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

	public function getLocations(){
		if(isset($_POST['connect'])){

			$servername = $this->validateData($_POST['servername']);
			$username = $this->validateData($_POST['username']);
			$password = $this->validateData($_POST['password']);
			$db = $this->validateData($_POST['database']);
			$port_no = $this->validateData($_POST['port_no']);
			
			$servername = 'p:'.$servername.':'.$port_no;		
			
			$conn = connectDB('p:'.$_POST['servername'].':'.$port_no,$_POST['username'],$_POST['password'],$_POST['database']);
				$lsql = "SELECT location_id,name FROM location";
				$locationquery = mysqli_query($conn,$lsql) or die(mysqli_error($conn));

				while($location = mysqli_fetch_assoc($locationquery)){
					echo '<option value="'.$location['location_id'].'">'.$location['name'].' ('.$location['location_id'].')</option>';
				}
			
		}
	}

	// CSV File Upload Function Declaration
	public function uploadCSV()
	{		
		
			// Connect to Database using submitted parameters
			$conn = connectDB($_POST['servername'],$_POST['username'],$_POST['password'],$_POST['db']);
			
			// Check Form Sumbit?
			if(isset($_POST["MigrateData"])){

				// Get the temporary file name of the CSV File
				$fileName = $_FILES["file"]["tmp_name"]; 

				// Disable foreign key and dependency/relationship checks in NMRS to allow for table manipulation
				mysqli_query($conn,"SET FOREIGN_KEY_CHECKS = 0");

				// mysqli_query($conn,"DELETE FROM obs WHERE concept_id = 0 OR value_coded = 0");
					
				// Ensure that the File is uploaded by checking the size
				if ($_FILES["file"]["size"] > 0) {

					// Determine the Data Category of the CSV
					switch($_POST['data_category']){

						case 'Demographics':
							
							// For Demographics CVS: 
							// List of Tables to update with data from the demographics CSV						
							$demographicsTables = array('patient','person','person_name','person_address','visit','patient_identifier','encounter','patient_program','obs');
							
							/* Setup Location Attributes 
							$locationid = $_POST['locationid'];
							$oldlocation = $_POST['oldlocation']; 
							mysqli_query($conn,"UPDATE location SET location_id='$locationid' WHERE location_id='$oldlocation'") or die(mysqli_error($conn));
							mysqli_query($conn,"UPDATE location_tag_map SET location_id='$locationid' WHERE location_id='$oldlocation'") or die(mysqli_error($conn));
							*/
							foreach ($demographicsTables as $key => $dtable) {
									
								
									$all_values = ""; // This will be used to hold all the values to enter into the table
									$visitArray = ""; // This container will be be moved to a text file to form First Visit Collections
									$row = 1; // Counter for each CSV Row
									/*
									if($dtable!='person'){
										echo "Truncate ".$dtable."<br>";
										mysqli_query($conn,"TRUNCATE $dtable") or die(mysqli_error($conn));
									}else{
										echo "Truncate ".$dtable."<br>";
										mysqli_query($conn,"DELETE FROM $dtable WHERE person_id!=1") or die(mysqli_error($conn));
									}
																		
									
									if($dtable=='patient_identifier' || $dtable=='patient_program' || $dtable=='obs' || $dtable=='encounter' || $dtable='visit'){
										mysqli_query($conn,"TRUNCATE $dtable") or die(mysqli_error($conn));
									}
									*/
									
									
									// Open up the file
									$file = fopen($fileName, "r");									
									$getfile = file($fileName);
									$rows = count($getfile); // Count the entire rows in the file

									$obsvalNumeric = ""; // To hold all value_numeric answers (values in obs table)
									$obsvalCoded = ""; // To hold all value_coded answers (values in obs table)
									$obsvalDateTime = ""; // To hold all value_datetime answers (values in obs table)
									$obsvalText = ""; // To hold all value_text answers (values in obs table)
									$obsvalOthers = ""; // To hold any other answers (values in obs table)


									// Loop throught the Uploaded CSV File
									while (($csvColumn = fgetcsv($file, 10000, ",")) !== FALSE) {
										
										$currentMigration = "Migrating ".$dtable." :". $csvColumn[0]."<hr>"; // Log the current process
										
										// Escape / Ignore the first row becuase it contains headings 
										// and we need the headings to be there so that the column count won't throw error
										if($row == 1){ $row++; continue; }

										if($csvColumn[26] == 'NULL' || $csvColumn[2]=='NULL'){ if($row<$rows){$row++; continue;}else{} }

											// Truncate and remove the contents of the existing tables if (necessary)
											// mysqli_query($conn,"TRUNCATE $dtable") or die(mysqli_error($conn));

											
											if($dtable=='patient_identifier'){
												
												/* 
													These are the NMRS Identifier Ttype IDs that will be used to 
													create the patient identifiers equivalent in NMRS 
													3 (Openmrs ID) mapped to IQnumber in the column[44] of the CSV File
													4 (ART Number) mapped to PatientEnrollmentID in the column[2] of the CSV File
													5 (Hospital Number) mapped to PatientClinicID in the column[3] of the CSV File
													6 (ANC Number) mapped to ANCNumber in the column[40] of the CSV File 
												*/
												$identifierList = array(3,4,5,6); 												
												$count_id = count($identifierList); // Count the total number of identifiers
												$r = 1;

												foreach($identifierList as $identifier){ // Looping through the identifiers

													
													/* Get the Function name to used to generate the SQl Column names in this case 
													'nmrspatient_identifierFields' as defined in the patient_identifierModel.php' */
													$nmrs_fields = 'nmrs'.$dtable.'Fields'; 	
													/* Get the Function name to used to generate the SQl values in this case 
													'seedcarepatient_identifierFields' as defined in the patient_identifierModel.php' */												
													$seedcare_fields = 'seedcare'.$dtable.'Fields';

													// Get Columns and arrays from the arrays stored in each functions
													$columns = implode(", ",call_user_func($nmrs_fields));													
													$values  = implode(",", call_user_func($seedcare_fields,$csvColumn,$identifier));
													
													if(($row*$r)<($rows*$count_id)){ // Check to see if we have NOT entered all the identifiers of the last CSV row
														if(getIdentifier($identifier,$csvColumn)=="NULL"){}else{
															$all_values.= "(".$values."),"; // Keep building the values collection
														}
													}else{ // As long as we have entered all the identifiers of the last CSV row
														if(getIdentifier($identifier,$csvColumn)=="NULL"){															
															$all_values = substr($all_values,0,-1);
														}else{
															$all_values.= "(".$values.")"; // Add add the last value to the values collection
														}
														// Write the SQL Statement and save to variable
														$demographicsSQL = "INSERT INTO `$dtable` ($columns) VALUES $all_values ON DUPLICATE KEY UPDATE voided=voided";
							
														// Execute the MySQLI Query
														$result = mysqli_query($conn, $demographicsSQL) or die(mysqli_error($conn));
													}
													$r++;													
												}
											}elseif($dtable=='obs'){

												// Get all the HIV Enrollment Dates to be used in the First Encounter, Visit, and Care Card(form)
												// Get the OBS Columns from the obsModel.php												
												
												
													$columns = obscolumns(); // Get the columns for entry in obs table
													$obsrows = hivEnrollentConcepts($csvColumn); // Get the Enrollment Forms Concepts as defined in the dictionary
													$obsrowcount = count($obsrows); // Count all the concepts needed
													$obsrowc = 1;
												
													foreach($obsrows as $obsrow){

														// For each of this concept is not value coded then return CSV's provided answer
														if($obsrow['conceptAns']!=""){
															$answer = $obsrow['conceptAns']; // For concept/coded answers
														}else{
															$answer = $obsrow['csvcol']; // For answers contained in the CSV File
														}	
														
														if($obsrow['conceptID']=='160535' && $csvColumn[37]=="NULL"){ $obsrowc++; continue;}
														if($obsrow['conceptID']=='160534' && $csvColumn[48]=="NULL"){ $obsrowc++; continue;}

														$eid = $csvColumn[0]+$_SESSION['maxvisit'];
														// Build the OBS Values
														$values="'".$csvColumn[0]."',"  // PatientID
								 						."'".$obsrow['conceptID']."',"
														."'".$eid."',null," // Encounter ID														
														."'".date("Y-m-d", strtotime($csvColumn[5]))."'," // Created at
														."'".$_POST['locationid']."',null,null,1," // Location ID	
														."'".$this->nmrsDateTime($csvColumn[24])."',"
														.$csvColumn[22]."," // deleteFlag
														."'".bin2hex(random_bytes(19))."','Care Card Form',"
														."'".$answer."'"; // Get the Answer

														// Build/ collect the SQL Values depending on datatype
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

														// Check the End of OBS Rows and Run the Query(s)
														if(($row*$obsrowc)==($rows*$obsrowcount)){															
															$this->checkQuery($dtable,$conn,substr($obsvalCoded,0,-1),'value_coded',$columns);
															$this->checkQuery($dtable,$conn,substr($obsvalNumeric,0,-1),'value_numeric',$columns);
															$this->checkQuery($dtable,$conn,substr($obsvalDateTime,0,-1),'value_datetime',$columns);
															$this->checkQuery($dtable,$conn,substr($obsvalText,0,-1),'value_text',$columns);
															$this->checkQuery($dtable,$conn,substr($obsvalOthers,0,-1),'value_text',$columns);
														}

														$obsrowc++;														
													}
											
											}elseif($dtable=='encounter'){

												$nmrs_fields = 'nmrs'.$dtable.'Fields'; // see line 170
												$seedcare_fields = 'seedcare'.$dtable.'Fields'; // see line 174												
												$columns = implode(", ",call_user_func($nmrs_fields));												
												$values  = implode(",", call_user_func($seedcare_fields,$csvColumn,$row,$_POST['data_category']));

												// Build the data that will be kept in the First visit collections Text File
												// These are patientID,ARTStartDate,PMTCTNumber,DOB
												$visitArray.=$csvColumn[0].','.$csvColumn[26].','.$csvColumn[41].','.$csvColumn[7]."\n";
												
												if($row<$rows){
													$all_values.= "(".$values."),";
												}else{
						
													// If the Last row is reach then write the sql
													$all_values.= "(".$values.")";
													$demographicsSQL = "INSERT INTO `$dtable` ($columns) VALUES $all_values ON DUPLICATE KEY UPDATE voided=voided";
						
													// Execute the MySQLI Query
													$result = mysqli_query($conn, $demographicsSQL) or die(mysqli_error($conn));

													// To be kept in a text file (last)
													$visitArray.=$csvColumn[0].','.$csvColumn[26].','.$csvColumn[41].','.$csvColumn[7];
													
													// save serialized data in a text file
													file_put_contents('firstVisit.txt', $visitArray);
												}

												// Increment row count
											}elseif($dtable=='visit'){
												$nmrs_fields = 'nmrs'.$dtable.'Fields';
												$seedcare_fields = 'seedcare'.$dtable.'Fields';

												// Get Columns from the arrays stored in each functions
												$columns = implode(", ",call_user_func($nmrs_fields));

												//$escaped_values = implode(',', (seedcareFields($csvColumn)));
												$values  = implode(",", call_user_func($seedcare_fields,$csvColumn,$_POST['data_category']));

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
							$clinicalTables = array('visit','encounter','obs'); // Tables to be impacted by clinical data
							mysqli_options($conn, MYSQLI_OPT_LOCAL_INFILE, true);
							

							// Load the first visit data
							$firstVisit = array();
							$vfile = explode("\n", file_get_contents("firstVisit.txt"));
							foreach ( $vfile as $content ) {
								$firstVisit[] = array_filter(array_map("trim", explode(",", $content)));
							}
								
							foreach ($clinicalTables as $key => $cltable) {
								
									//Load the Clinical CSV Data (A form of dictionary of nmrs mapped concepts)
									$clinicalCSV = array_map('str_getcsv', file('assets/resources/clinicals.csv'));
									
									// List all the columns that will be used to generate obs data according to the Clinical CSV Uploaded
									$obsColumnNos = array(3,4,5,6,7,8,9,10,14,15,16,17,18,19,20,21,22,23,24,25);

									$countObsFields = count($obsColumnNos); // Total number of columns

									$all_values = "";
									$row = 1;
									
									// Open up the file
									$file = fopen($fileName, "r");
									
									$getfile = file($fileName);
									$rows = count($getfile);

									$obsvalNumeric = "";
									$obsvalCoded = "";
									$obsvalDateTime = "";
									$obsvalOthers = "";
									$obsvalText = "";

									// Loop through the Uploaded CSV File
									while (($csvColumn = fgetcsv($file, 10000, ",")) !== FALSE) {
										
										// $currentMigration = "Migrating ".$cltable." :". $csvColumn[0]."<hr>";										

										// Escape / Ignore the first row becuase it contains headings 
										// and we need the headings to be there so that the column count won't throw error
										if($row == 1){ $row++; continue; }
										if(patientExists($csvColumn[0],$firstVisit)=="NonART"){if($row<$rows){$row++; continue;}else{}}

											// Truncate and remove the contents of the existing tables
											// mysqli_query($conn,"TRUNCATE $cltable") or die(mysqli_error($conn));

											
											if($cltable=='obs'){
												// Get the OBS Columns from the obsModel.php
												
												$ocount = 1;
												

												foreach($obsColumnNos as $obsrow){													
													$columns = obscolumns();
													//Check if OBS Row is NULL
													if($csvColumn[$obsrow]=="NULL" || $csvColumn[$obsrow]==""){
														if(($row*$ocount)!=($rows*$countObsFields)){
															$ocount++;
															continue;
														}else{	
															/*								
															$this->checkQuery($cltable,$conn,$obsvalNumeric,'value_numeric',$columns);
															$this->checkQuery($cltable,$conn,$obsvalCoded,'value_coded',$columns);
															$this->checkQuery($cltable,$conn,$obsvalDateTime,'value_datetime',$columns);
															$this->checkQuery($cltable,$conn,$obsvalOthers,'value_text',$columns);
															*/

															file_put_contents('obsvalCoded.csv', $obsvalCoded);
															file_put_contents('obsvalNumeric.csv', $obsvalNumeric);
															file_put_contents('obsvalDateTime.csv', $obsvalDateTime);
															file_put_contents('obsvalText.csv', $obsvalText);
															file_put_contents('obsvalOthers.csv', $obsvalOthers);

															$obsvalNumeric = "";
															$obsvalCoded = "";
															$obsvalDateTime = "";
															$obsvalOthers = "";
															$obsvalText = "";

															// Value Numeric SQL
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalNumeric.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_numeric)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Coded SQL
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalCoded.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_coded)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Datetime SQl
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalDateTime.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_datetime)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Text SQl
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalText.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_text)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Others SQl
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalOthers.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_text)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

														}
											
													}else{
														
														$dictionary = new clinicalDictionary;
														/* 
														$values="'".$csvColumn[0]."',"
								 						."'".$dictionary->getCID($clinicalCSV,$obsrow)."',"
														."'".$csvColumn[2]."',"
														."'".$csvColumn[2]."',"
														."'".$this->nmrsDateTime($csvColumn[12])."',"
														."'".$csvColumn[1]."',"
														."'".$this->getObsGroupID("")."',"
														."'".$row."',1,"
														."'".$this->nmrsDateTime($csvColumn[12])."',0,"
														."'".bin2hex(random_bytes(18))."','Care Card Form',"
														."'".$dictionary->getAns($clinicalCSV,$obsrow,$csvColumn[$obsrow])."'";

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
														*/
																
														$values=$csvColumn[0].",".$dictionary->getCID($clinicalCSV,$obsrow).",".$csvColumn[2].",\N,".$this->nmrsDateTime($csvColumn[26]).",".$_POST['locationid'].",\N,,1,".$this->nmrsDateTime($csvColumn[26]).",0,".bin2hex(random_bytes(18)).",Care Card Form,".$dictionary->getAns($clinicalCSV,$obsrow,$csvColumn[$obsrow]);

													   switch (obsValueType($clinicalCSV,$obsrow)){
														   case "value_numeric":																	
															   $obsvalNumeric.=$values."\n";
															   break;

														   case "value_coded":																	
															   $obsvalCoded.=$values."\n";
															   break;

														   case "value_datetime":																	
															   $obsvalDateTime.=$values."\n";
															   break;

														   default:																	
															   $obsvalOthers.=$values."\n";
															   break;
														}

														if(($row*$ocount)==($rows*$countObsFields)){

															file_put_contents('obsvalCoded.csv', $obsvalCoded);
															file_put_contents('obsvalNumeric.csv', $obsvalNumeric);
															file_put_contents('obsvalDateTime.csv', $obsvalDateTime);
															file_put_contents('obsvalText.csv', $obsvalText);
															file_put_contents('obsvalOthers.csv', $obsvalOthers);

															$obsvalNumeric = "";
															$obsvalCoded = "";
															$obsvalDateTime = "";
															$obsvalOthers = "";
															$obsvalText = "";
															
															// Value Numeric SQL
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalNumeric.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_numeric)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Coded SQL
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalCoded.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_coded)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Datetime SQl
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalDateTime.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_datetime)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Text SQl
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalText.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_text)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Others SQl
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalOthers.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_text)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

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
												$values  = implode(",", call_user_func($seedcare_fields,$csvColumn,$row,$_POST['data_category']));

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
												$values  = implode(",", call_user_func($seedcare_fields,$csvColumn,$_POST['data_category']));

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
							// Load the first visit data
							$firstVisit = array();
							$vfile = explode("\n", file_get_contents("firstVisit.txt"));
							mysqli_options($conn, MYSQLI_OPT_LOCAL_INFILE, true);
							foreach ( $vfile as $content ) {
								$firstVisit[] = array_filter(array_map("trim", explode(",", $content)));
							}
							
							$pharmacyTables = array('encounter','visit','obs');
							// $pharmacyTables = array('obs');								
							foreach ($pharmacyTables as $key => $phtable) {
									//Load the Drug Coding CSV Data									
									$drugCoding = array_map('str_getcsv', file('assets/resources/drugcoding2.csv'));

									$regimenCoding = array_map('str_getcsv', file('assets/resources/regimencoding.csv'));

									$all_values = ""; // SQL values container
									$row = 1;
									$obsidcount = 0;
									$groupid=null;
									// Open up the file
									$file = fopen($fileName, "r");
									
									$getfile = file($fileName);
									$rows = count($getfile);

									$obsvalNumeric = "";
									$obsvalCoded = "";
									$obsvalDateTime = "";
									$obsvalText = "";
									$obsvalOthers = "";
									$visitArray = array();
									$multiplier=$_SESSION['maxvisit']*20;
									$chunk=0;

									// Loop through the Uploaded CSV File
									while (($csvColumn = fgetcsv($file, 10000, ",")) !== FALSE) {
										
										
										$currentMigration = "Migrating ".$phtable." :". $csvColumn[0]."<hr>";
										// Count all the rows

										// Escape / Ignore the first row becuase it contains headings 
										// and we need the headings to be there so that the column count won't throw error
										if($row == 1){ $row++; continue; }

										// if($csvColumn[24]!="ARV Medication"){if($row<$rows){$row++; continue;}else{} }
										// if($csvColumn[25]!="ARV Medication"){if($row<$rows){$row++; continue;}else{} }
										
											// Truncate and remove the contents of the existing tables
											// mysqli_query($conn,"TRUNCATE $cltable") or die(mysqli_error($conn));
											
											if($phtable=='obs'){
												// Get the OBS Columns from the obsModel.php
												
												
													$columns = obscolumns();
													$obsrows = pharmacyConcepts($csvColumn,$firstVisit,$regimenCoding);
													$obsrowcount = count($obsrows);
													$obsrowc = 1;
													
													if(array_search($csvColumn[2],$visitArray)!==FALSE){}else{
														foreach($obsrows as $obsrow){
															
																
																if($obsrow['conceptAns']!=""){
																	$answer = $obsrow['conceptAns'];
																}else{
																	$answer = $obsrow['csvcol'];
																}
		
																	$values=",".$csvColumn[1].","
																	.$obsrow['conceptID'].","
																	.$csvColumn[2].",\N,"
																	.date("Y-m-d", strtotime($csvColumn[5])).","
																	.$_POST['locationid'].",\N,,1,"
																	.$this->nmrsDateTime($csvColumn[5]).",0,"
																	.bin2hex(random_bytes(19)).",Pharmacy Form,"
																	.$answer;																
		
																	switch ($obsrow['dataType']){
																		case "value_numeric":																	
																			$obsvalNumeric.=$values."\n";
																			break;
			
																		case "value_coded":																	
																			$obsvalCoded.=$values."\n";
																			break;
			
																		case "value_datetime":																	
																			$obsvalDateTime.=$values."\n";
																			break;
																		
																		case "value_text":																	
																			$obsvalText.=$values."\n";
																			break;
			
																		default:																	
																			$obsvalOthers.=$values."\n";
																			break;
																	}

																	array_push($visitArray,$csvColumn[2]);															

															
															$obsrowc++;															
																											
														}
													}
													// Check the End of OBS Rows
													
													
													// DRUG STARTS
													$drugrows = pharmacyDrugs($csvColumn,$firstVisit,$drugCoding);
												
													foreach($drugrows as $drugrow){
														$obsidcount++;
														if($drugrow['conceptAns']!=""){
															$answer = $drugrow['conceptAns'];																
														}else{
															$answer = $drugrow['csvcol'];
														}

														if($drugrow['conceptID']==162240 || $drugrow['conceptID']==165726){
															$obs_id = $multiplier+$obsidcount;
															$groupid=$obs_id;																
														}else{
															$obs_id = "";
														}

														if($obs_id!=""){
															$values=$obs_id.",".$csvColumn[1].","
															.$drugrow['conceptID'].","
															.$csvColumn[2].",\N,"
															.date("Y-m-d", strtotime($csvColumn[5])).","
															.$_POST['locationid'].",\N,,1,"																
															.$this->nmrsDateTime($csvColumn[5]).",0,"
															.bin2hex(random_bytes(19)).",Pharmacy Form,"
															.$answer;
														}else{
															$values=$obs_id.",".$csvColumn[1].","
															.$drugrow['conceptID'].","
															.$csvColumn[2].",\N,"
															.date("Y-m-d", strtotime($csvColumn[5])).","
															.$_POST['locationid'].","
															.$groupid.",,1,"
															.$this->nmrsDateTime($csvColumn[5]).",0,"
															.bin2hex(random_bytes(19)).",Pharmacy Form,"
															.$answer;
														}

														switch ($drugrow['dataType']){
															case "value_numeric":																	
																$obsvalNumeric.=$values."\n";
																break;

															case "value_coded":																	
																$obsvalCoded.=$values."\n";
																break;

															case "value_datetime":																	
																$obsvalDateTime.=$values."\n";
																break;
															
															case "value_text":																	
																$obsvalText.=$values."\n";
																break;

															default:																	
																$obsvalOthers.=$values."\n";
																break;
														}
													}
													//DRG ENDS
													
													// if(($row*$obsrowc)>=($rows*$obsrowcount)){	
													// if(($row)>=($rows)){	
													if($chunk==5000 || $row==$rows){
														echo $row."<br>";
														/*													
														$result = $this->checkQuery($phtable,$conn,$obsvalCoded,'value_coded',$columns);															
														$this->checkQuery($phtable,$conn,$obsvalNumeric,'value_numeric',$columns);
														$this->checkQuery($phtable,$conn,$obsvalDateTime,'value_datetime',$columns);
														$this->checkQuery($phtable,$conn,$obsvalText,'value_text',$columns);
														$this->checkQuery($phtable,$conn,$obsvalOthers,'value_text',$columns);	
														*/

														file_put_contents('obsvalCoded.csv', $obsvalCoded);
														file_put_contents('obsvalNumeric.csv', $obsvalNumeric);
														file_put_contents('obsvalDateTime.csv', $obsvalDateTime);
														file_put_contents('obsvalText.csv', $obsvalText);
														file_put_contents('obsvalOthers.csv', $obsvalOthers);

														$obsvalNumeric = "";
														$obsvalCoded = "";
														$obsvalDateTime = "";
														$obsvalOthers = "";
														$obsvalText = "";

														// Value Numeric SQL
														$sql = "LOAD DATA LOCAL INFILE  
														'obsvalNumeric.csv'
														INTO TABLE obs  
														FIELDS TERMINATED BY ',' 															
														LINES TERMINATED BY '\n'
														(obs_id,$columns,value_numeric)";
														$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

														// Value Coded SQL
														$sql = "LOAD DATA LOCAL INFILE  
														'obsvalCoded.csv'
														INTO TABLE obs  
														FIELDS TERMINATED BY ',' 															
														LINES TERMINATED BY '\n'
														(obs_id,$columns,value_coded)";
														$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

														// Value Datetime SQl
														$sql = "LOAD DATA LOCAL INFILE  
														'obsvalDateTime.csv'
														INTO TABLE obs  
														FIELDS TERMINATED BY ',' 															
														LINES TERMINATED BY '\n'
														(obs_id,$columns,value_datetime)";
														$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

														// Value Text SQl
														$sql = "LOAD DATA LOCAL INFILE  
														'obsvalText.csv'
														INTO TABLE obs  
														FIELDS TERMINATED BY ',' 															
														LINES TERMINATED BY '\n'
														(obs_id,$columns,value_text)";
														$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

														// Value Others SQl
														$sql = "LOAD DATA LOCAL INFILE  
														'obsvalOthers.csv'
														INTO TABLE obs  
														FIELDS TERMINATED BY ',' 															
														LINES TERMINATED BY '\n'
														(obs_id,$columns,value_text)";
														$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));	
														
														$chunk = 0;
													}	

													$chunk++;
																							

											}elseif($phtable=='encounter'){
												$nmrs_fields = 'nmrs'.$phtable.'Fields';
												$seedcare_fields = 'seedcare'.$phtable.'Fields';

												// Get Columns from the arrays stored in each functions
												$columns = implode(", ",call_user_func($nmrs_fields));

												//$escaped_values = implode(',', (seedcareFields($csvColumn)));
												$values  = implode(",", call_user_func($seedcare_fields,$csvColumn,$row,$_POST['data_category']));

												if($row<$rows){
													$all_values.= "(".$values."),";
												}else{
						
													// If the Last row is reach then write the sql
													$all_values.= "(".$values.")";
													$clinicalsSQL = "INSERT INTO `$phtable`($columns) VALUES $all_values ON DUPLICATE KEY UPDATE voided=voided";
						
													// Execute the MySQLI Query
													$result = mysqli_query($conn, $clinicalsSQL) or die(mysqli_error($conn));
												}
											}else{

											
												$nmrs_fields = 'nmrs'.$phtable.'Fields';
												$seedcare_fields = 'seedcare'.$phtable.'Fields';

												// Get Columns from the arrays stored in each functions
												$columns = implode(", ",call_user_func($nmrs_fields));

												//$escaped_values = implode(',', (seedcareFields($csvColumn)));
												$values  = implode(",", call_user_func($seedcare_fields,$csvColumn,$_POST['data_category']));

												if($row<$rows){
													$all_values.= "(".$values."),";
												}else{
						
													// If the Last row is reach then write the sql
													$all_values.= "(".$values.")";
													$clinicalsSQL = "INSERT INTO `$phtable`($columns) VALUES $all_values ON DUPLICATE KEY UPDATE voided=voided";
						
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
										
										echo "<div class='alert alert-success'> $phtable's CSV Data has been Imported into the Database</div>";
									} else {
										echo "<div class='alert alert-danger'>Problem in Importing $phtable's  CSV Data</div>";
									}

										
							}			
							
						break;

						case 'Lab':
							$firstVisit = array();
							$Vfile = explode("\n", file_get_contents("firstVisit.txt"));
							mysqli_options($conn, MYSQLI_OPT_LOCAL_INFILE, true);
							foreach ( $Vfile as $content ) {
								$firstVisit[] = array_filter(array_map("trim", explode(",", $content)));
							}

							//Load the lAB CSV Data
							$labCSV = array_map('str_getcsv', file('assets/resources/labcoding.csv'));
							
							$labTables = array('obs','encounter','visit');
								
							foreach ($labTables as $key => $ltable) {
								
								// List all the columns that will be used to generate obs data according to the CSV Uploaded
								$all_values = "";
								$row = 1;
								
								// Open up the file
								$file = fopen($fileName, "r");
								
								$getfile = file($fileName);
								$rows = count($getfile);

								$obsvalNumeric = "";
								$obsvalCoded = "";
								$obsvalDateTime = "";
								$obsvalOthers = "";
								$obsvalText = "";

								// Loop through the Uploaded CSV File
								while (($csvColumn = fgetcsv($file, 10000, ",")) !== FALSE) {
									
									$currentMigration = "Migrating ".$ltable." :". $csvColumn[0]."<hr>";
									// Count all the rows

									// Escape / Ignore the first row becuase it contains headings 
									// and we need the headings to be there so that the column count won't throw error
									if($row == 1){ $row++; continue; }

									if(patientExists($csvColumn[2],$firstVisit)=="NonART" || $csvColumn[19]=="NULL"){if($row<$rows){$row++; continue;}else{} }
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

										
										if($ltable=='obs'){
											// Get the OBS Columns from the obsModel.php
											
											
											

											
												// $columns = $obscolumns.",".obsValueType($csvColumn,$obsrow);
												$columns = obscolumns();
																						
													$dictionary = new labDictionary;
													/*
													$values=$csvColumn[2].","
													."'".$dictionary->getCID($labCSV,$csvColumn[11])."',"
													."'".$csvColumn[0]."',"
													."'".$csvColumn[0]."',"
													."'".$this->nmrsDateTime($csvColumn[5])."',"
													."'".$csvColumn[3]."',"
													."'".$this->getObsGroupID($csvColumn[12])."',"
													."'".$csvColumn[0]."',1,"														
													."'".$this->nmrsDateTime($csvColumn[5])."',0,"
													."'".bin2hex(random_bytes(18))."','Laboratory Order and Result form',"
													."'".$dictionary->getAns($labCSV,$csvColumn[11],$csvColumn[19])."'";

																										
														
													switch (obsValueTypeLab($labCSV,$csvColumn[11])){
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
															echo $csvColumn[11]."-".$dictionary->getCID($labCSV,$csvColumn[11])."<hr>";										
															$obsvalOthers.="(".$values."),";
															break;
													}
													*/
													$values=$csvColumn[2].","
													.$dictionary->getCID($labCSV,$csvColumn[11]).","
													.$csvColumn[1].",\N,"
													.date("Y-m-d", strtotime($csvColumn[5])).","
													.$_POST['locationid'].",\N,,1,"																											
													.$this->nmrsDateTime($csvColumn[5]).",0,"
													.bin2hex(random_bytes(18)).",Laboratory Order and Result form,"
													.$dictionary->getAns($labCSV,$csvColumn[11],$csvColumn[19]);


													switch (obsValueTypeLab($labCSV,$csvColumn[11])){
														case "value_numeric":																	
															$obsvalNumeric.=$values."\n";
															break;

														case "value_coded":																	
															$obsvalCoded.=$values."\n";
															break;

														case "value_datetime":																	
															$obsvalDateTime.=$values."\n";
															break;

														default:																	
															$obsvalOthers.=$values."\n";
															break;
													}

													if($row==$rows){
														/*
														$result = $this->checkQuery($ltable,$conn,$obsvalCoded,'value_coded',$columns);
														$result = $this->checkQuery($ltable,$conn,$obsvalNumeric,'value_numeric',$columns);
														$this->checkQuery($ltable,$conn,$obsvalDateTime,'value_datetime',$columns);
														$this->checkQuery($ltable,$conn,$obsvalText,'value_text',$columns);
														$this->checkQuery($ltable,$conn,$obsvalOthers,'value_text',$columns);
														*/

														file_put_contents('obsvalCoded.csv', $obsvalCoded);
															file_put_contents('obsvalNumeric.csv', $obsvalNumeric);
															file_put_contents('obsvalDateTime.csv', $obsvalDateTime);
															file_put_contents('obsvalText.csv', $obsvalText);
															file_put_contents('obsvalOthers.csv', $obsvalOthers);

															$obsvalNumeric = "";
															$obsvalCoded = "";
															$obsvalDateTime = "";
															$obsvalOthers = "";
															$obsvalText = "";

															// Value Numeric SQL
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalNumeric.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_numeric)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Coded SQL
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalCoded.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_coded)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Datetime SQl
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalDateTime.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_datetime)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Text SQl
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalText.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_text)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Others SQl
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalOthers.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_text)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

													}

										}elseif($ltable=='encounter'){
											$nmrs_fields = 'nmrs'.$ltable.'Fields';
											$seedcare_fields = 'seedcare'.$ltable.'Fields';

											// Get Columns from the arrays stored in each functions
											$columns = implode(", ",call_user_func($nmrs_fields));
											//$escaped_values = implode(',', (seedcareFields($csvColumn)));
											$values  = implode(",", call_user_func($seedcare_fields,$csvColumn,$row,$_POST['data_category']));

											if($row<$rows){
												$all_values.= "(".$values."),";
											}else{
					
												// If the Last row is reach then write the sql
												$all_values.= "(".$values.")";
												$clinicalsSQL = "INSERT INTO `$ltable`($columns) VALUES $all_values ON DUPLICATE KEY UPDATE voided=voided";
					
												// Execute the MySQLI Query
												$result = mysqli_query($conn, $clinicalsSQL) or die(mysqli_error($conn));
											}
										}else{

										
											$nmrs_fields = 'nmrs'.$ltable.'Fields';
											$seedcare_fields = 'seedcare'.$ltable.'Fields';

											// Get Columns from the arrays stored in each functions
											$columns = implode(", ",call_user_func($nmrs_fields));

											//$escaped_values = implode(',', (seedcareFields($csvColumn)));
											$values  = implode(",", call_user_func($seedcare_fields,$csvColumn,$_POST['data_category']));

											if($row<$rows){
												$all_values.= "(".$values."),";
											}else{					
												// If the Last row is reach then write the sql
												$all_values.= "(".$values.")";
												$clinicalsSQL = "INSERT INTO `$ltable`($columns) VALUES $all_values ON DUPLICATE KEY UPDATE voided=voided";
					
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
									echo "<div class='alert alert-success'> $ltable's CSV Data has been Imported into the Database</div>";
								} else {
									echo "<div class='alert alert-danger'>Problem in Importing $ltable's CSV Data</div>";
								}

									
							}	
						break;

						case 'Inactive':
									mysqli_options($conn, MYSQLI_OPT_LOCAL_INFILE, true);
									$phtable = 'obs';
									$all_values = ""; // SQL values container
									$row = 1;
									
									// Open up the file
									$file = fopen($fileName, "r");
									
									$getfile = file($fileName);
									$rows = count($getfile);

									$obsvalNumeric = "";
									$obsvalCoded = "";
									$obsvalDateTime = "";
									$obsvalText = "";
									$obsvalOthers = "";

									// Loop through the Uploaded CSV File
									while (($csvColumn = fgetcsv($file, 10000, ",")) !== FALSE) {
										
										$currentMigration = "Migrating obs :". $csvColumn[0]."<hr>";
										// Count all the rows

										// Escape / Ignore the first row becuase it contains headings 
										// and we need the headings to be there so that the column count won't throw error
										if($row == 1){ $row++; continue; }
										
											// Truncate and remove the contents of the existing tables
											// mysqli_query($conn,"TRUNCATE $cltable") or die(mysqli_error($conn));
											
											
												// Get the OBS Columns from the obsModel.php
												
												
												$columns = obscolumns();

													$obsrows = inactiveConcepts($csvColumn);
													$obsrowcount = count($obsrows);
													$obsrowc = 1;
													
													foreach($obsrows as $obsrow){

														if($obsrow['conceptAns']!=""){
															$answer = $obsrow['conceptAns'];
														}else{
															$answer = $obsrow['csvcol'];
														}

														/*

														$values="'".$csvColumn[1]."',"
								 						."'".$obsrow['conceptID']."',"
														."'".$csvColumn[2]."',"
														."'".$csvColumn[2]."',"
														."'".$this->nmrsDateTime($csvColumn[5])."',"
														."'".$csvColumn[1]."',"
														."'".$this->getObsGroupID($csvColumn[1])."',"
														."'".$csvColumn[2]."',1,"
														."'".$this->nmrsDateTime($csvColumn[5])."',0,"
														."'".bin2hex(random_bytes(19))."','Pharmacy Form',"
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
														*/
														$values=$csvColumn[1].","
								 						.$obsrow['conceptID'].","
														.$csvColumn[2].","
														.$csvColumn[2].","
														.$this->nmrsDateTime($csvColumn[5]).","
														.$_POST['locationid'].","
														.$this->getObsGroupID($csvColumn[1]).","
														.$csvColumn[2].",1,"
														.$this->nmrsDateTime($csvColumn[5]).",0,"
														.bin2hex(random_bytes(19)).",Client Tracking and Termination,"
														.$answer;

														switch ($obsrow['dataType']){
															case "value_numeric":																	
																$obsvalNumeric.=$values."\n";
																break;
 
															case "value_coded":																	
																$obsvalCoded.=$values."\n";
																break;
 
															case "value_datetime":																	
																$obsvalDateTime.=$values."\n";
																break;
 
															default:																	
																$obsvalOthers.=$values."\n";
																break;
														}
 
														// Check the End of OBS Rows

														if(($row*$obsrowc)==($rows*$obsrowcount)){		
															/*													
															$result = $this->checkQuery($phtable,$conn,$obsvalCoded,'value_coded',$columns);															
															$this->checkQuery($phtable,$conn,$obsvalNumeric,'value_numeric',$columns);
															$this->checkQuery($phtable,$conn,$obsvalDateTime,'value_datetime',$columns);
															$this->checkQuery($phtable,$conn,$obsvalText,'value_text',$columns);
															$this->checkQuery($phtable,$conn,$obsvalOthers,'value_text',$columns);	
															*/

															file_put_contents('obsvalCoded.csv', $obsvalCoded);
															file_put_contents('obsvalNumeric.csv', $obsvalNumeric);
															file_put_contents('obsvalDateTime.csv', $obsvalDateTime);
															file_put_contents('obsvalText.csv', $obsvalText);
															file_put_contents('obsvalOthers.csv', $obsvalOthers);

															$obsvalNumeric = "";
															$obsvalCoded = "";
															$obsvalDateTime = "";
															$obsvalOthers = "";
															$obsvalText = "";

															// Value Numeric SQL
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalNumeric.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_numeric)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Coded SQL
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalCoded.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_coded)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Datetime SQl
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalDateTime.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_datetime)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Text SQl
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalText.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_text)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

															// Value Others SQl
															$sql = "LOAD DATA LOCAL INFILE  
															'obsvalOthers.csv'
															INTO TABLE obs  
															FIELDS TERMINATED BY ',' 															
															LINES TERMINATED BY '\n'
															($columns,value_text)";
															$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
															
														}
														$obsrowc++;												
													}			
											
										$row++;
										$currentMigration="";											
									}
																													
									// Check if we have reached the last row (because it helps to right just one insert query)
									// if not continue building up data to be uploaded once (helps to optimise)								
										
									if (!empty($result)) {
										echo "<div class='alert alert-success'> $phtable's CSV Data has been Imported into the Database</div>";
									} else {
										echo "<div class='alert alert-danger'>Problem in Importing $phtable's  CSV Data</div>";
									}

										
										
							
						break;
						
						case 'Termination':
							mysqli_options($conn, MYSQLI_OPT_LOCAL_INFILE, true);
							$phtable = 'obs';
							$all_values = ""; // SQL values container
							$row = 1;
							
							// Open up the file
							$file = fopen($fileName, "r");
							
							$getfile = file($fileName);
							$rows = count($getfile);

							$obsvalNumeric = "";
							$obsvalCoded = "";
							$obsvalDateTime = "";
							$obsvalText = "";
							$obsvalOthers = "";

							// Loop through the Uploaded CSV File
							while (($csvColumn = fgetcsv($file, 10000, ",")) !== FALSE) {
								
								$currentMigration = "Migrating obs :". $csvColumn[0]."<hr>";
								// Count all the rows

								// Escape / Ignore the first row becuase it contains headings 
								// and we need the headings to be there so that the column count won't throw error
								if($row == 1){ $row++; continue; }
								
									// Truncate and remove the contents of the existing tables
									// mysqli_query($conn,"TRUNCATE $cltable") or die(mysqli_error($conn));
									
									
										// Get the OBS Columns from the obsModel.php										
										
										$columns = obscolumns();

											$obsrows = terminationConcepts($csvColumn);
											$obsrowcount = count($obsrows);
											$obsrowc = 1;
											
											foreach($obsrows as $obsrow){

												if($obsrow['conceptAns']!=""){
													$answer = $obsrow['conceptAns'];
												}else{
													$answer = $obsrow['csvcol'];
												}
											
												$values=$csvColumn[0].","
												 .$obsrow['conceptID'].",null,null,"												
												.$this->nmrsDateTime($csvColumn[4]).","
												.$_POST['locationid'].",null,null,1,"
												.$this->nmrsDateTime($csvColumn[5]).",0,"
												.bin2hex(random_bytes(19)).",Client Tracking and Termination,"
												.$answer;

												switch ($obsrow['dataType']){
													case "value_numeric":																	
														$obsvalNumeric.=$values."\n";
														break;

													case "value_coded":																	
														$obsvalCoded.=$values."\n";
														break;

													case "value_datetime":																	
														$obsvalDateTime.=$values."\n";
														break;

													default:																	
														$obsvalOthers.=$values."\n";
														break;
												}

												// Check the End of OBS Rows

												if(($row*$obsrowc)==($rows*$obsrowcount)){		
													/*													
													$result = $this->checkQuery($phtable,$conn,$obsvalCoded,'value_coded',$columns);															
													$this->checkQuery($phtable,$conn,$obsvalNumeric,'value_numeric',$columns);
													$this->checkQuery($phtable,$conn,$obsvalDateTime,'value_datetime',$columns);
													$this->checkQuery($phtable,$conn,$obsvalText,'value_text',$columns);
													$this->checkQuery($phtable,$conn,$obsvalOthers,'value_text',$columns);	
													*/

													file_put_contents('obsvalCoded.csv', $obsvalCoded);
													file_put_contents('obsvalNumeric.csv', $obsvalNumeric);
													file_put_contents('obsvalDateTime.csv', $obsvalDateTime);
													file_put_contents('obsvalText.csv', $obsvalText);
													file_put_contents('obsvalOthers.csv', $obsvalOthers);

													$obsvalNumeric = "";
													$obsvalCoded = "";
													$obsvalDateTime = "";
													$obsvalOthers = "";
													$obsvalText = "";

													// Value Numeric SQL
													$sql = "LOAD DATA LOCAL INFILE  
													'obsvalNumeric.csv'
													INTO TABLE obs  
													FIELDS TERMINATED BY ',' 															
													LINES TERMINATED BY '\n'
													($columns,value_numeric)";
													$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

													// Value Coded SQL
													$sql = "LOAD DATA LOCAL INFILE  
													'obsvalCoded.csv'
													INTO TABLE obs  
													FIELDS TERMINATED BY ',' 															
													LINES TERMINATED BY '\n'
													($columns,value_coded)";
													$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

													// Value Datetime SQl
													$sql = "LOAD DATA LOCAL INFILE  
													'obsvalDateTime.csv'
													INTO TABLE obs  
													FIELDS TERMINATED BY ',' 															
													LINES TERMINATED BY '\n'
													($columns,value_datetime)";
													$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

													// Value Text SQl
													$sql = "LOAD DATA LOCAL INFILE  
													'obsvalText.csv'
													INTO TABLE obs  
													FIELDS TERMINATED BY ',' 															
													LINES TERMINATED BY '\n'
													($columns,value_text)";
													$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

													// Value Others SQl
													$sql = "LOAD DATA LOCAL INFILE  
													'obsvalOthers.csv'
													INTO TABLE obs  
													FIELDS TERMINATED BY ',' 															
													LINES TERMINATED BY '\n'
													($columns,value_text)";
													$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
													
												}
												$obsrowc++;												
											}			
									
								$row++;
								$currentMigration="";											
							}
																											
							// Check if we have reached the last row (because it helps to right just one insert query)
							// if not continue building up data to be uploaded once (helps to optimise)								
								
							if (!empty($result)) {
								echo "<div class='alert alert-success'> $phtable's CSV Data has been Imported into the Database</div>";
							} else {
								echo "<div class='alert alert-danger'>Problem in Importing $phtable's  CSV Data</div>";
							}

								
								
					
						break;

						case 'Users':
							// List of Tables to update with data from the demographics CSV
							
							$usersTables = array('users');
									
							foreach ($usersTables as $key => $utable) {
									$all_values = "";								
									$row = 1;

									// Open up the file
									$file = fopen($fileName, "r");
									
									$getfile = file($fileName);
									$rows = count($getfile);

									// Loop throught the Uploaded CSV File
									while (($csvColumn = fgetcsv($file, 10000, ",")) !== FALSE) {
										
										$currentMigration = "Migrating ".$utable." :". $csvColumn[0]."<hr>";
										// Count all the rows

										// Escape / Ignore the first row becuase it contains headings and first row because it is admin record
										// and we need the headings to be there so that the column count won't throw error
										if($row <= 2){ $row++; continue; }

											// Truncate and remove the contents of the existing tables
											// mysqli_query($conn,"TRUNCATE $dtable") or die(mysqli_error($conn));

												$nmrs_fields = 'nmrs'.$utable.'Fields';
												$seedcare_fields = 'seedcare'.$utable.'Fields';

												// Get Columns from the arrays stored in each functions
												$columns = implode(", ",call_user_func($nmrs_fields));

												//$escaped_values = implode(',', (seedcareFields($csvColumn)));
												$values  = implode(",", call_user_func($seedcare_fields,$csvColumn));

												if($row<$rows){
													$all_values.= "(".$values."),";
												}else{
						
													// If the Last row is reach then write the sql
													$all_values.= "(".$values.")";
													$usersSQL = "INSERT INTO `$utable` ($columns) VALUES $all_values ON DUPLICATE KEY UPDATE person_id=person_id";
						
													// Execute the MySQLI Query
													$result = mysqli_query($conn, $usersSQL) or die(mysqli_error($conn));
												}

												// Increment row count
											
											
										$row++;
										$currentMigration="";
											
										}
																			
										
										// Check if we have reached the last row (because it helps to right just one insert query)
										// if not continue building up data to be uploaded once (helps to optimise)
										
										
										if (!empty($result)) {
											echo "<div class='alert alert-success'> $utable's CSV Data has been Imported into the Database</div>";											
										} else {
											echo "<div class='alert alert-danger'>Problem in Importing CSV Data</div>";
										}

										
									}
							
							
							
							break;

							default:
							echo "<div class='alert alert-danger'>Please select a data category from the list</div>";
						break; 

					}
						
					
					// Reactivate the Foreign_Key Checks so that table can be related properly
					mysqli_query($conn,"SET FOREIGN_KEY_CHECKS = 1");

			
				}else{
					echo "<div class='alert alert-danger'>Please select a CSV file to upload<hr><a href='javascript:history.back()' class='btn btn-success'>Go Back</a></a></div>";
				}
			}
		
	}	
 
}
?>