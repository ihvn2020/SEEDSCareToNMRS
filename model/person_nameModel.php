<?php

// Column names in NMRS Patient Table
function nmrsperson_nameFields(){
    $nmrsperson_nameColumns = array( 
        'person_name_id',       
        'person_id', // Ptn_Pk
        'given_name', // Firstname
        'middle_name', // Middlename
        'family_name', //Lastname      
        'voided', // Delete Flag
        'creator',
        'uuid'
    );

    return $nmrsperson_nameColumns;
        
}

//Column Names in Seed Care
function seedcareperson_nameFields($csvColumn){
    $seedcareperson_nameColumns = array(
        $csvColumn[0], // Ptn_Pk
        $csvColumn[0],
        "'".$csvColumn[33]."'", // Firstname
        "'".$csvColumn[30]."'", // Middlename
        "'".$csvColumn[34]."'", // Lastname
        $csvColumn[22], // DeleteFlag
        1, // $csvColumn[23], // Supposed to be userID but is null
        "'".bin2hex(random_bytes(6))."'"
    );

    return $seedcareperson_nameColumns;
        
}

?>