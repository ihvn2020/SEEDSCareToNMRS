<?php

// Mapping patient tables columns/fields to Demographics' table CSV
function mappedFields($csvColumn){
    $nmrsColumns = array(
        'patient_id'=>$csvColumn[0], // Ptn_Pk
        'creator'=>$csvColumn[23], // UserID
        'date_created'=>$csvColumn[24], // CreateDate
        'changed_by'=>$csvColumn[23], // UserID
        'date_changed'=>$csvColumn[25], // UpdateDate
        'voided'=>$csvColumn[22], // Delete Flag
        'voided_by'=>$csvColumn[23], // UserID
        'void_reason'=>$csvColumn[21],
        'allergy_status'=>$csvColumn[18]
    );

    return $nmrsColumns;
        
}

// Column names in NMRS Patient Table
function nmrspatientFields(){
    $nmrspatientColumns = array(
        'patient_id', // Ptn_Pk
        'creator', // UserID
        'date_created', // CreateDate
        'changed_by', // UserID
        'date_changed', // UpdateDate
        'voided', // Delete Flag
        'voided_by', // UserID
        'void_reason', // Null
        'allergy_status' // 
    );

    return $nmrspatientColumns;
        
}

//Column Names in Seed Care
function seedcarepatientFields($csvColumn){
    $seedcarepatientColumns = array(
        $csvColumn[0], // Ptn_Pk
        1, //$csvColumn[23], // UserID We use one for now because the demographics table has no creator value
        "'".date("Y-m-d", strtotime($csvColumn[24]))."'",  // CreateDate
        $csvColumn[23], // UserID
        $csvColumn[25], // UpdateDate
        $csvColumn[22], // Delete Flag
        $csvColumn[23], // UserID
        $csvColumn[21], // Notes
        "'".$csvColumn[18]."'" // Status
    );

    return $seedcarepatientColumns;
        
}

?>