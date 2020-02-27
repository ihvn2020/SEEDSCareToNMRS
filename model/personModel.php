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
        'creator'
    );

    return $nmrspersonColumns;
        
}

//Column Names in Seed Care
function seedcarepersonFields($csvColumn){
    $seedcarepersonColumns = array(
        $csvColumn[0], // Ptn_Pk
        1, //$csvColumn[23], // UserID We use one for now because the demographics table has no creator value
        $csvColumn[6], // Sex
        $csvColumn[7], // DOB
        0, // $csvColumn[25], // DobPrecision
        0, // $csvColumn[?], // No Death Record in Seedscare
        $csvColumn[22], // DeleteFlag
        1 // $csvColumn[23], // Supposed to be userID but is null
    );

    return $seedcarepersonColumns;
        
}

?>