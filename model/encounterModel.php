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

function getEncounterID($csvColumn){
    if(is_int($csvColumn)){
        return $csvColumn[2];
    }else{
        return $csvColumn[0];
    }
}
//Column Names in Seed Care
function seedcareencounterFields($csvColumn){
    $seedcareencounterColumns = array(
        getEncounterID($csvColumn[2]), // Encounter ID
        5,
        "'".$csvColumn[0]."'", // Patient ID
        "'".$csvColumn[1]."'", // Location ID
        5,        
        "'".date("Y-m-d", strtotime($csvColumn[12]))."'",
        "'".$csvColumn[11]."'", // Creator 
        "'".date("Y-m-d", strtotime($csvColumn[12]))."'",        
        0, // Voided
        $csvColumn[2], // Visit ID       
        "'".bin2hex(random_bytes(6))."'"
    );

    return $seedcareencounterColumns;
        
}

?>