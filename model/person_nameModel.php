<?php

// Column names in NMRS Patient Table
function nmrsperson_nameFields(){
    $nmrsperson_nameColumns = array( 
        'person_name_id',       
        'person_id', // Ptn_Pk
        'given_name', // Firstname
        'middle_name', // Middlename
        'family_name', //Lastname  
        'date_created',    
        'voided', // Delete Flag
        'creator',
        'uuid'
    );

    return $nmrsperson_nameColumns;
        
}

function noNull($middlename){
    if($middlename=='NULL'){
        return '';
    }else{
        return $middlename;
    }
}

function filterName($name){
    if (!strpos($name, "'") !== false) {
        return $name;
    }else{
        return str_replace("'","\'",$name);
    }
}

//Column Names in Seed Care
function seedcareperson_nameFields($csvColumn){
    $seedcareperson_nameColumns = array(
        $csvColumn[0], // Ptn_Pk
        $csvColumn[0],
        "'".filterName($csvColumn[33])."'", // Firstname
        "'".filterName(noNull($csvColumn[30]))."'", // Middlename
        "'".filterName($csvColumn[34])."'", // Lastname
        "'".date("Y-m-d", strtotime($csvColumn[24]))."'",
        $csvColumn[22], // DeleteFlag        
        1, // $csvColumn[23], // Supposed to be userID but is null
        "'".bin2hex(random_bytes(18))."'"
    );

    return $seedcareperson_nameColumns;
        
}

?>