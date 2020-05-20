<?php

// Column names in NMRS Patient Table
function nmrspatient_programFields(){
    $nmrspatient_programColumns = array( 
        'patient_id',       
        'program_id', // Program ID
        'date_enrolled', // Date Enrolled
        'location_id', // Location ID
        'creator',
        'uuid'
    );

    return $nmrspatient_programColumns;
        
}

//Column Names in Seed Care
function seedcarepatient_programFields($csvColumn){
    $seedcarepatient_programColumns = array(
        $csvColumn[0], // Ptn_Pk
        1,
        "'".date("Y-m-d", strtotime($csvColumn[5]))."'",
        $_POST['locationid'], // DeleteFlag        
        1, // Creator
        "'".bin2hex(random_bytes(18))."'"
    );

    return $seedcarepatient_programColumns;
        
}

?>