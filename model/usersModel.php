<?php

// Column names in NMRS Patient Table
function nmrsusersFields(){
    $nmrsusersColumns = array(
        'user_id',
        'username',	
        'password',
        'date_created',
        'date_changed',       
        'retired',
        'uuid'
    );
    return $nmrsusersColumns;        
}

//Column Names in Seed Care
function seedcareusersFields($csvColumn){
       
        $seedcareusersColumns = array(
            $csvColumn[0], // users ID
            "'".$csvColumn[3]."',". // Username
            "'".$csvColumn[4]."',". // Password         
            "'".date("Y-m-d", strtotime(str_replace($csvColumn[7],"/","-")))."'", // Date Created
            "'".date("Y-m-d", strtotime(str_replace($csvColumn[8],"/","-")))."'", // Date Changeed
            $csvColumn[5], // Voided
            "'".bin2hex(random_bytes(19))."'"
        );
    

    return $seedcareusersColumns;
        
}

