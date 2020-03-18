<?php




// Column names in NMRS Patient Table
function nmrsvisitFields(){
    $nmrsvisitColumns = array(
        'visit_id',
        'patient_id',	
        'visit_type_id',
        'date_started',
        'date_stopped',
        'location_id',	
        'creator',	
        'date_created',	
        'voided',
        'uuid'
    );

    return $nmrsvisitColumns;
        
}

//Column Names in Seed Care
function seedcarevisitFields($csvColumn){
    if(is_int($csvColumn[1])){ // For Demographics First Visit Creation
        $seedcarevisitColumns = array(
            $csvColumn[0], // Visit ID
            $csvColumn[0], // Patient ID
            1, // Visit Type ID (Facility Visit)            
            "'".date("Y-m-d", strtotime($csvColumn[12]))."'", // Date Started
            "'".date("Y-m-d", strtotime($csvColumn[12]))."'", // Date Stopped
            $csvColumn[1], // Location ID
            "'".$csvColumn[11]."'", // Creator
            "'".date("Y-m-d", strtotime($csvColumn[12]))."'",        
            0, // Voided
            "'".bin2hex(random_bytes(6))."'"
        );
    }else{ // For Pharmacy Visit Creation
        $seedcarevisitColumns = array( 
            $csvColumn[2], // Visit ID
            $csvColumn[0], // Patient ID
            1, // Visit Type ID (Facility Visit)            
            "'".date("Y-m-d", strtotime($csvColumn[5]))."'", // Date Started
            "'".date("Y-m-d", strtotime($csvColumn[5]))."'", // Date Stopped
            $csvColumn[3], // Location ID
            1, // Creator
            "'".date("Y-m-d", strtotime($csvColumn[5]))."'",        
            0, // Voided
            "'".bin2hex(random_bytes(6))."'"
        );
    }

    return $seedcarevisitColumns;
        
}

