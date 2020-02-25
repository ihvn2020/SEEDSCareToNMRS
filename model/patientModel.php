<?php

// Mapping patient tables columns/fields to Demographics' table CSV
function patientFields($csvColumn){
    $patientColumns = array(
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

    return $patientColumns;
        
}

?>