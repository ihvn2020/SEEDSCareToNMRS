<?php


// Column names in NMRS Patient Table
function nmrspatient_identifierFields(){
    $nmrspatient_identifierColumns = array(
        'patient_identifier_id', // Ptn_Pk
        'patient_id', // Ptn_Pk
        'identifier', // 
        'identifier_type', // Array Key of the identifierList;
        'location_id', // UpdateDate
        'voided', // Delete Flag
        'uuid'
    );

    return $nmrspatient_identifierColumns;
        
}

function getIdType($identifier){
// $csvColumn[39]=>3,$csvColumn[13]=>5,$csvColumn[11]=>6,$csvColumn[3]=>7,$csvColumn[36]=>8,$csvColumn[20]=>11
    if($identifier===3){
        return $csvColumn[45]; // IQNumber -> OpenMRS ID  
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

function getIdName($identifier){
    // $csvColumn[39]=>3,$csvColumn[13]=>5,$csvColumn[11]=>6,$csvColumn[3]=>7,$csvColumn[36]=>8,$csvColumn[20]=>11
        if($identifier===3){
            return 'OpenMRS ID'; 
        }elseif($identifier==4){
            return 'ART Number';
        }elseif($identifier==5){
            return 'Hospital Number';
        }elseif($identifier==6){
            return 'ANC Number';        
        }else{
            return null;
        }    
    }
    

//Extracted Column Names in Seed Care for Patient Identifier
function seedcarepatient_identifierFields($csvColumn){
    
    $seedcarepatient_identifierColumns = array(
        
        $csvColumn[0], //$Patient Name, // UserID We use one for now because the demographics table has no creator value
        getIdName($identifier), // Get Identifyier Name (later)
        getIdType($identifier), // UserID
        $csvColumn[1], // Location ID
        $csvColumn[22], // Delete Flag
        $csvColumn[0]      
    );

    return $seedcarepatient_identifierColumns;
        
}

?>