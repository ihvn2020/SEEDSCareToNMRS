<?php

// Column names in NMRS Patient Table
function nmrspatientFields(){
    $nmrspatientColumns = array(
        'patient_id', // Ptn_Pk
        'creator', // UserID
        'date_created', // CreateDate
        'changed_by', // UserID
        'date_changed', // UpdateDate
        'voided', // Delete Flag
        'voided_by', // UserID
        'allergy_status' // 
    );

    return $nmrspatientColumns;
        
}

//Column Names in Seed Care
function seedcarepatientFields($csvColumn){
    $seedcarepatientColumns = array(
        $csvColumn[0], // Ptn_Pk
        1, //$csvColumn[23], // UserID We use one for now because the demographics table has no creator value
        "'".date("Y-m-d", strtotime($csvColumn[5]))."'",  // RegistrationDate
        $csvColumn[23], // UserID
        "'".date("Y-m-d", strtotime($csvColumn[25]))."'",        
        $csvColumn[22], // Delete Flag
        $csvColumn[23], // UserID
        "'".$csvColumn[18]."'" // Status
    );

    return $seedcarepatientColumns;
        
}

?>