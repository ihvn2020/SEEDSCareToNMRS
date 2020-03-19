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
        $formid = 21;
    }elseif($data_category=="Demographics"){
        $encounterID = $csvColumn[2];
        $formid = 27;
    }elseif($data_category=="Pharmacy"){
        $encounterID = $csvColumn[28];
        $formid = 27;
    }else{
        $encounterID = $csvColumn[2];
        $formid = 14;
    }


        $seedcareencounterColumns = array(
            $encounterID, // Encounter ID
            5,
            $csvColumn[0], // Patient ID
            $csvColumn[1], // Location ID
            5,        
            "'".date("Y-m-d", strtotime($csvColumn[12]))."'",
            "'".$csvColumn[11]."'", // Creator 
            "'".date("Y-m-d", strtotime($csvColumn[12]))."'",        
            0, // Voided
            $csvColumn[0], // Visit ID       
            "'".bin2hex(random_bytes(6))."'"
        );
    
    return $seedcareencounterColumns;
        
}

?>