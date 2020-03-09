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
    $seedcarevisitColumns = array(
        $csvColumn[2], // Encounter ID
        5,
        "'".$csvColumn[0]."'", // Patient ID
        5, // Visit Type ID               
        "'".date("Y-m-d", strtotime($csvColumn[12]))."'",
        "'".date("Y-m-d", strtotime($csvColumn[12]))."'",
        $csvColumn[1], // Location ID
        "'".$csvColumn[11]."'", // Creator
        "'".date("Y-m-d", strtotime($csvColumn[12]))."'",        
        0, // Voided
        "'".bin2hex(random_bytes(6))."'"
    );

    return $seedcarevisitColumns;
        
}

?>