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
        $visitid = $csvColumn[0];  
        $datestarted = $csvColumn[5];
        $datestopped = $csvColumn[9]; 
        $locationid = $csvColumn[3];   
        $patientid = $csvColumn[1];
        $voided = $csvColumn[22];
    }elseif($data_category=="Demographics"){
        $visitid = $csvColumn[0];  
        $datestarted = $csvColumn[26];
        $datestopped = $csvColumn[26];
        $locationid = $csvColumn[1];    
        $patientid = $csvColumn[0];   
        $voided = $csvColumn[22];
    }elseif($data_category=="Pharmacy"){
        $visitid = $csvColumn[2];  
        $datestarted = $csvColumn[5];
        $datestopped = $csvColumn[7];    
        $locationid = $csvColumn[3];  
        $patientid = $csvColumn[0]; 
        $voided = $csvColumn[22];
    }else{
        $visitid = $csvColumn[2];
        $datestarted = $csvColumn[12];
        $datestopped = $csvColumn[12]; 
        $locationid = $csvColumn[1];
        $patientid = $csvColumn[0]; 
        $voided = $csvColumn[22];
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

