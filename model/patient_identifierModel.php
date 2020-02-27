<?php


// Column names in NMRS Patient Table
function nmrspatient_identifierFields(){
    $nmrspatient_identifierColumns = array(
        'patient_identifier_id', // Ptn_Pk
        'patient_id', // Ptn_Pk
        'identifier', // identifierList[]->name
        'identifier_type', // Array Key of the identifierList;
        'location_id', // UpdateDate
        'voided' // Delete Flag
    );

    return $nmrspatient_identifierColumns;
        
}

$identifierList = (object) array($csvColumn[39]=>3,$csvColumn[13]=>5,$csvColumn[11]=>6,$csvColumn[3]=>7,$csvColumn[36]=>8,$csvColumn[20]=>11);

function getIdType($identifier){
    return $identifierList->$identifier;
}

function getIdType($identifier){
    return $identifierList->$identifier;
}
//Extracted Column Names in Seed Care for Patient Identifier
function seedcarepatient_identifierFields($csvColumn){
    
    $seedcarepatient_identifierColumns = array(
        
        $csvColumn[0], //$csvColumn[23], // UserID We use one for now because the demographics table has no creator value
        getIdName($identifier), // Get Identifyier Name (later)
        getIdType($identifier), // UserID
        $csvColumn[1], // UpdateDate
        $csvColumn[22] // Delete Flag      
    );

    return $seedcarepatient_identifierColumns;
        
}

?>