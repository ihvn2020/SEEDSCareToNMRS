<?php


// Column names in NMRS Patient Table
function nmrspatient_identifierFields(){
    $nmrspatient_identifierColumns = array(
        
        'patient_id', // Ptn_Pk
        'identifier', // 
        'identifier_type', // Array Key of the identifierList;
        'location_id', // UpdateDate
        'creator',
        'voided', // Delete Flag
        'uuid'
    );

    return $nmrspatient_identifierColumns;
        
}

function getIdentifier($identifier,$csvColumn){
// $csvColumn[39]=>3,$csvColumn[13]=>5,$csvColumn[11]=>6,$csvColumn[3]=>7,$csvColumn[36]=>8,$csvColumn[20]=>11
    if($identifier==3){
        return $csvColumn[44]; // IQNumber -> OpenMRS ID  
    }elseif($identifier==4){   
        return $csvColumn[2];   // PatientEnrollmentID ->ART Number
    }elseif($identifier==5){
        return $csvColumn[3];  // PatientClinicID -> Hospital Number
    }elseif($identifier==6){
        return $csvColumn[40]; // ANCNumber -> ANC Number
    }else{
        return null;
    }    
}  

//Extracted Column Names in Seed Care for Patient Identifier
function seedcarepatient_identifierFields($csvColumn,$identifier){
    
    $seedcarepatient_identifierColumns = array(
        
        $csvColumn[0], //$Patient Name, // UserID We use one for now because the demographics table has no creator value
        "'".getIdentifier($identifier,$csvColumn)."'", // Get Identifyier Name (later)
        $identifier, // Id Type
        $csvColumn[1], // Location ID
        1,
        $csvColumn[22], // Delete Flag
        "'".bin2hex(random_bytes(6))."'"      
    );

    return $seedcarepatient_identifierColumns;
        
}

?>