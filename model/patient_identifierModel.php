<?php


// Column names in NMRS Patient Table
function nmrspatient_identifierFields(){
    $nmrspatient_identifierColumns = array(
        'patient_identifier_id', // Ptn_Pk
        'patient_id', // Ptn_Pk
        'identifier', // identifierList[]->name
        'identifier_type', // Array Key of the identifierList;
        'location_id', // UpdateDate
        'voided', // Delete Flag
        'uuid'
    );

    return $nmrspatient_identifierColumns;
        
}

function getIdName($identifierList){
    return key($identifierList);
}

function getIdType($identifier){
    return $identifierList;
}


//Extracted Column Names in Seed Care for Patient Identifier
function seedcarepatient_identifierFields($csvColumn){
    
    $seedcarepatient_identifierColumns = array(
        
        $csvColumn[0], //$csvColumn[23], // UserID We use one for now because the demographics table has no creator value
        getIdName($identifierList), // Get Identifyier Name (later)
        getIdType($identifier), // UserID
        $csvColumn[1], // UpdateDate
        $csvColumn[22], // Delete Flag
        $csvColumn[0]      
    );

    return $seedcarepatient_identifierColumns;
        
}

?>