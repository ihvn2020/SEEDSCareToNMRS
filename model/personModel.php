<?php

// Column names in NMRS Patient Table
function nmrspersonFields(){
    $nmrspersonColumns = array(
        'person_id', // Ptn_Pk
        'gender', // Sex
        'birthdate', // DOB
        'birthdate_estimated',
        'dead',        
        'voided', // Delete Flag
        'creator',
        'uuid'
    );

    return $nmrspersonColumns;
        
}

//Column Names in Seed Care
function seedcarepersonFields($csvColumn){
    $seedcarepersonColumns = array(
        "'".$csvColumn[2]."'", // Ptn_Pk
        "'".$csvColumn[6]."'", // Sex
        "'".date("Y-m-d", strtotime($csvColumn[7]))."'",         
        "'".$csvColumn[25]."'", // DobPrecision
        0, // $csvColumn[?], // No Death Record in Seedscare
        "'".$csvColumn[22]."'", // DeleteFlag
        1, // $csvColumn[23], // Supposed to be userID but is null
        "'".bin2hex(random_bytes(6))."'"
    );

    return $seedcarepersonColumns;
        
}

?>