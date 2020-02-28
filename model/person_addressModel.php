<?php

// Column names in NMRS Patient Table
function nmrsperson_addressFields(){
    $nmrsperson_addressColumns = array(
        'person_name_id', // Ptn_Pk
        'person_id', // Ptn_Pk
        'given_name', // Firstname
        'middle_name', // Middlename
        'family_name', //Lastname      
        'voided', // Delete Flag
        'creator'
    );

    return $nmrsperson_addressColumns;
        
}

//Column Names in Seed Care
function seedcareperson_addressFields($csvColumn){
    $seedcareperson_addressColumns = array(
        $csvColumn[0], // Ptn_Pk
        1, //$csvColumn[23], // UserID We use one for now because the demographics table has no creator value
        $csvColumn[33], // Firstname
        $csvColumn[30], // Middlename
        $csvColumn[34], // Lastname
        $csvColumn[22], // DeleteFlag
        1 // $csvColumn[23], // Supposed to be userID but is null
    );

    return $seedcareperson_addressColumns;
        
}

?>