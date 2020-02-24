<?php

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
 
}