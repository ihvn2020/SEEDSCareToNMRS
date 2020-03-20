<?php




// Column names in NMRS Patient Table
function nmrsusersFields(){
    $nmrsusersColumns = array(
        'user_id',
        'username',	
        'password',
        'date_created',
        'date_changed',       
        'voided',
        'uuid'
    );

    return $nmrsusersColumns;
        
}

//Column Names in Seed Care
function seedcareusersFields($csvColumn){
       
        $seedcareusersColumns = array(
            $csvColumn[0], // users ID
            $csvColumn[3], // Username
            $csvColumn[4], // Password         
            "'".date("Y-m-d", strtotime($csvColumn[7]))."'", // Date Started
            "'".date("Y-m-d", strtotime($csvColumn[8]))."'", // Date Stopped
            "'".$locationid."'", // Creator
            "'".date("Y-m-d", strtotime($datestarted))."'",        
            $csvColumn[5], // Voided
            "'".bin2hex(random_bytes(6))."'"
        );
    

    return $seedcareusersColumns;
        
}

