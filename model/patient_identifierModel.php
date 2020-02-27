<?php


// Column names in NMRS Patient Table
function nmrspatient_identifierFields(){
    $nmrspatient_identifierColumns = array(
        'patient_identifier_id', // Ptn_Pk
        'patient_id', // UserID
        'identifier', // CreateDate
        'identifier_type', // UserID
        'location_id', // UpdateDate
        'voided' // Delete Flag
    );

    return $nmrspatient_identifierColumns;
        
}

//Extracted Column Names in Seed Care for Patient Identifier
function seedcarepatient_identifierFields($csvColumn){
    $identifierList = array($csvColumn[39]=>3,$csvColumn[13]=>5,$csvColumn[11]=>6,$csvColumn[3]=>7,$csvColumn[36]=>8,$csvColumn[20]=>11);
    
    $seedcarepatient_identifierColumns = array(
        getId(), // Ptn_Pk
        $csvColumn[0], //$csvColumn[23], // UserID We use one for now because the demographics table has no creator value
        "", // Get Identifyier Name (later)
        getIdType(), // UserID
        $csvColumn[1], // UpdateDate
        $csvColumn[22] // Delete Flag      
    );

    return $seedcarepatient_identifierColumns;
        
}

?>