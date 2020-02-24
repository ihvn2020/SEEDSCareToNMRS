<?php

//get connection parameters
// require 'config/database.php';
class seedcareToNMRS {
    private $facilityName = "";
	private $datimId = "";
 
	function __construct( $facilityName, $datimId ) {
		$this->facilityName = $facilityName;
		$this->datimId = $datimId;
	}
 
	function getFileType() {
		return $this->name;
	}
 
}