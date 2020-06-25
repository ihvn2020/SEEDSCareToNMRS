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
function seedcarevisitFields($csvColumn,$data_category){
    if($data_category=='Lab'){
        $visitid = $csvColumn[1];  
        $datestarted = $csvColumn[5];
        $datestopped = $csvColumn[9]; 
        $locationid = $_POST['locationid'];   
        $patientid = $csvColumn[2];
        $voided = 0;
    }elseif($data_category=="Demographics"){
        $visitid = $csvColumn[0]+$_SESSION['maxvisit'];  
        $datestarted = $csvColumn[5];
        $datestopped = $csvColumn[5];
        $locationid = $_POST['locationid'];    
        $patientid = $csvColumn[0];   
        $voided = $csvColumn[22];
    }elseif($data_category=="Pharmacy"){
        $visitid = $csvColumn[2];  
        $datestarted = $csvColumn[5];
        $datestopped = $csvColumn[7];    
        $locationid = $_POST['locationid'];  
        $patientid = $csvColumn[1]; 
        $voided = 0;
    }else{
        $visitid = $csvColumn[2];
        $datestarted = $csvColumn[26];
        $datestopped = $csvColumn[26]; 
        $locationid = $_POST['locationid'];
        $patientid = $csvColumn[0]; 
        $voided = 0;
    }
   
        $seedcarevisitColumns = array(
            $visitid, // Visit ID
            $patientid, // Patient ID
            1, // Visit Type ID (Facility Visit)            
            "'".date("Y-m-d", strtotime($datestarted))."'", // Date Started
            "'".date("Y-m-d", strtotime($datestopped))."'", // Date Stopped
            $locationid, // Location ID
            1, // Creator
            "'".date("Y-m-d", strtotime($datestarted))."'",        
            $voided, // Voided
            "'".bin2hex(random_bytes(18))."'"
        );
    

    return $seedcarevisitColumns;
        
}

// Just to Confirm