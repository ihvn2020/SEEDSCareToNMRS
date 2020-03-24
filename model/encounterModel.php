<?php

// Column names in NMRS Patient Table
function nmrsencounterFields(){
    $nmrsencounterColumns = array(
        'encounter_id',
        'encounter_type',	
        'patient_id',
        'location_id',	
        'form_id',	
        'encounter_datetime',	
        'creator',	
        'date_created',	
        'voided',
        'visit_id',	
        'uuid'
    );

    return $nmrsencounterColumns;
        
}

//Column Names in Seed Care
function seedcareencounterFields($csvColumn,$row,$data_category){
    if($data_category=='Lab'){
        $encounterID = $csvColumn[0];
        $formid = 19; // Laboratory Order and Result Form (Need Verification)
        $encounterTypeID = 5;
        $locationid = $csvColumn[1]; 
        $encounterdate = date("Y-m-d", strtotime($csvColumn[26]));
        $voided = $csvColumn[22];
        $visitid = $csvColumn[0]; 
        $patientid = $csvColumn[0];      
    }elseif($data_category=="Demographics"){
        $encounterID = $csvColumn[0];
        $formid = 21; // HIV Enrollment Form (Need Verification)
        $encounterTypeID = 14;
        $locationid = $csvColumn[1];
        $encounterdate = date("Y-m-d", strtotime($csvColumn[26]));
        $voided = $csvColumn[22];
        $visitid = $csvColumn[0];
        $patientid = $csvColumn[0];
    }elseif($data_category=="Pharmacy"){
        $encounterID = $csvColumn[28];
        $formid = 25; // Pharmacy Order Form (Need Verification)
        $encounterTypeID = 5;
        $locationid = $csvColumn[1];
        $encounterdate = date("Y-m-d", strtotime($csvColumn[26]));
        $voided = $csvColumn[22];
        $visitid = $csvColumn[0];
        $patientid = $csvColumn[0];
    }else{ // Clinicals
        $encounterID = $csvColumn[2];
        $formid = 12; // Care card form / Clinicals Encounter // (Need Verification)
        $encounterTypeID = 5;
        $locationid = $csvColumn[1];
        $encounterdate = date("Y-m-d", strtotime($csvColumn[12]));
        $voided = 0;
        $visitid = $csvColumn[2];
        $patientid = $csvColumn[0];
    }

    //******************IMPORTANT****************//
    // Need to generate Adult Initial Clinical Evaluation
    // And Ped Initial Clinical Evalutation


        $seedcareencounterColumns = array(
            $encounterID, // Encounter ID
            $encounterTypeID,
            $patientid, // Patient ID
            $locationid, // Location ID
            $formid,        
            "'".$encounterdate."'",
            1, // Creator 
            "'".$encounterdate."'",        
            $voided, // Voided
            $visitid, // Visit ID       
            "'".bin2hex(random_bytes(18))."'"
        );
    
    return $seedcareencounterColumns;
        
}

?>