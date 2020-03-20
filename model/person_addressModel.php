<?php

// Column names in NMRS Patient Table
function nmrsperson_addressFields(){
    $nmrsperson_addressColumns = array(
        'person_address_id', // Ptn_Pk
        'person_id', // Ptn_Pk
        'address1', // Address
        'city_village', // 
        'state_province', // 
        'country', //      
        'voided', // Delete Flag
        'creator',
        'uuid'
    );

    return $nmrsperson_addressColumns;
        
}

//Column Names in Seed Care
function seedcareperson_addressFields($csvColumn){
    $seedcareperson_addressColumns = array(
        $csvColumn[0], // Ptn_Pk
        $csvColumn[0], // Ptn_Pk
        $csvColumn[35], // Address
        "'".$csvColumn[9]." ".$csvColumn[10]." ".$csvColumn[11]."'", // VillageName
        $csvColumn[12], // Province
        $csvColumn[27], // CountryId
        $csvColumn[22], // DeleteFlag
        1, // $csvColumn[23], // Supposed to be userID but is null
        "'".bin2hex(random_bytes(18))."'"
    );

    return $seedcareperson_addressColumns;
        
}

?>