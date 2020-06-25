<?php

function obsValueType($clinicalCSV,$obsrow){

   foreach($clinicalCSV as $line){
      if($line[2] != $obsrow){
          continue;
      }else if($line[2] == $obsrow){      
          return $line[3];
      }else {
          return "value_text";
      }
  }

}

function obsValueTypeLab($labCSV,$obsrow){

    foreach($labCSV as $line){
       if($line[0] != $obsrow){
           continue;
       }else if($line[0] == $obsrow){      
           return $line[3];
       }else {
           return "value_text";
       }
   }
 
 }

//List of OBS Columns that will be impacted by Seedcare Data

function obscolumns(){
    return "person_id,
    concept_id,
    encounter_id,
    order_id,
    obs_datetime,
    location_id,
    obs_group_id,
    accession_number,
    creator,
    date_created,
    voided,
    uuid,
    form_namespace_and_path";
}

function oneNull($val){
    if($val=="NULL" || $val=="0"){
        return 1;
    }else{
        return $val;
    }
}

function ifNull($val){
    if($val=="NULL" || $val=="0"){
        return 1;
    }else{
        return $val;
    }
}

function getCivilAns($maritalStatus){
    switch($maritalStatus){
        case "Single":
            return 1057;
            break;

        case "Married":
            return 5555;
            break;
        
        case "Widowed":
            return 1059;
            break;

        case "Divorced":
            return 1058;
            break;
            
        case "Seperated":
            return 1056;
            break;
        
        case "Living with Partner":
            return 1060;
            break;

        default:
            return 1057;
    }
}

function getEduLevel($education){
    switch($education){
        case "No Education":
            return 1107;
            break;

        case "Primary School Education":
            return 1713;
            break;
        
        case "Secondary School Education":
            return 1714;
            break;

        case "Tertiary Education Complete":
            return 160292;
            break;
            
        case "Other":
            return 5622;
            break;

        default:
            return 5622;
    }
}

function getPMTCT($pmtct){
    if($pmtct!="NULL"){
        return 'true';
    }else{
        return 'false';
    }
}

function nxkName($csvColumn){
    if($csvColumn[51]!="NULL"){
        return filterAddress($csvColumn[51]);

    }else{
        return "";
    }

}

function nxkNo($csvColumn){
    if($csvColumn[53]!="NULL" || $csvColumn[53]!=""){
        return $csvColumn[27].$csvColumn[53];
    }else{
        return $csvColumn[36];
    }
}

function nxkRelationship($csvColumn){
    switch($csvColumn[52]){
        case 'Parent':
            return 1527;    

        break;

        case 'Spouse/Partner':
            return 164945;    

        break;

        case 'Sister/Brother':
            return 160730;    

        break;

        case 'NULL':
            return 5622;    

        break;

        default:
        return 5622;

    }
}
function hivEnrollentConcepts($csvColumn){
    $obsCols = array(
        // Date of HIV Enrollment
        array(
            "conceptID" => 160554,
            "dataType" => "value_datetime",
            "conceptAns" => "",
            "csvcol" => $csvColumn[5]
        ),
        // Mode of HIV Test - HIVAB
        array(
            "conceptID" => 164947,
            "dataType" => "value_coded",
            "conceptAns" => 164949,
            "csvcol" => ""
        ),
        // Method of Enrollment - VCT
        array(
            "conceptID" => 160540,
            "dataType" => "value_coded",
            "conceptAns" => 160539,
            "csvcol" => ""
        ),

        // Method of Enrollment - VCT
        array(
            "conceptID" => 1542,
            "dataType" => "value_coded",
            "conceptAns" => 1175,
            "csvcol" => ""
        ),

        // Previously on ART
        array(
            "conceptID" => 165766,
            "dataType" => "value_text",
            "conceptAns" => "",
            "csvcol" => "No"
        ),

        // Facility Transffered From
        array(
            "conceptID" => 160535,
            "dataType" => "value_text",
            "conceptAns" => "",
            "csvcol" => $csvColumn[37]
        ),

        // Date Transferred to SEEDSCARE Facility
        array(
            "conceptID" => 160534,
            "dataType" => "value_datetime",
            "conceptAns" => "",
            "csvcol" => $csvColumn[48]
        ),

        // ART Treatment Start Date
        array(
            "conceptID" => 159599,
            "dataType" => "value_datetime",
            "conceptAns" => "",
            "csvcol" => $csvColumn[26]
        ),

        // Marital/Civil Status
        array(
            "conceptID" => 1054,
            "dataType" => "value_coded",
            "conceptAns" => getCivilAns($csvColumn[13]),
            "csvcol" => ""
        ),

        // Highest Education
        array(
            "conceptID" => 1712,
            "dataType" => "value_coded",
            "conceptAns" => getEduLevel($csvColumn[14]),
            "csvcol" => ""
        ),

        // Currently pregnant on Enrollment
        array(
            "conceptID" => 1434,
            "dataType" => "value_text",
            "conceptAns" => "",
            "csvcol" => getPMTCT($csvColumn[41])
        ),

        // Contact Number
        array(
            "conceptID" => 159635,
            "dataType" => "value_text",
            "conceptAns" => "",
            "csvcol" => "234".$csvColumn[36],
        ),

        // Next of Kin Name
        array(
            "conceptID" => 162729,
            "dataType" => "value_text",
            "conceptAns" => "",
            "csvcol" => nxkName($csvColumn),
        ),
        

        // Next of Kin Number
        array(
            "conceptID" => 164946,
            "dataType" => "value_text",
            "conceptAns" => "",
            "csvcol" => nxkNo($csvColumn)
        ),

         // Next of Kin Relationship
         array(
            "conceptID" => 164943,
            "dataType" => "value_coded",
            "conceptAns" => nxkRelationship($csvColumn),
            "csvcol" => ""
        )

        // Contact Relationship
        
        
      );

      return $obsCols;
}

function getTreatmentType($ttype){
    if($ttype!="NULL"){
        return 165303;
    }else{
        return 165941;
    }
}

function getVisitType($csvColumn){

        if($csvColumn[26]!=$csvColumn[34]){
            return 160530;
        }else{
            return 164180;
        }
}

function patientExists($patientNo,$firstVisit){
    
    if(!isset($firstVisit[$patientNo][0])){
        return "NonART";
    }
}

function getPregnancyStatus($csvColumn){    
    if($csvColumn[35]!='NULL'){
        return 165048;
    }else{
        return 165047;
    }
}

function getPickupReason($csvColumn){
    
    if($csvColumn[26]!=$csvColumn[34]){
        return 165662;
    }else{
        return 165773;
    }
}

function getAgeGroup($csvColumn){    

        if($csvColumn[32]<15){
            return 1528;
        }else{
            return 165709;
        }
    
}

function getRegimenLine($csvColumn){
    switch(getAgeGroup($csvColumn)){
        case 1528: // Child
            if(substr($csvColumn[18], -2)=='/r' || substr($csvColumn[18], -2)=='-r'){
                return 164514; // Child Second Line
            }else{
                return 164507; // Child First Line
            }
        break;

        case 165709: // Adult
            if(substr($csvColumn[18], -2)=='/r'  || substr($csvColumn[18], -2)=='-r'){
                return 164513; // Adult Second Line
            }else{
                return 164506; // Adult First Line
            }
        break;

        default:
        return 5622;
    }
}

function getARVRegimen($csvColumn,$regimenCoding){
    //$csvColumn[15]; // Drugname
    $arvreginmen = 5622;
    foreach($regimenCoding as $line){
        if($csvColumn[18]=="NULL" || $csvColumn[18]=="0"  || $csvColumn[18]==""){
            $arvreginmen = 5622;
        }else if($line[0]!=$csvColumn[18]){
            continue;
        }else if($line[0]==$csvColumn[18]){      
            if($line[1]!=""){
                $arvreginmen = $line[1];
            }else{
                $arvreginmen = 5622;
            }
        break;
        }else {
            $arvreginmen = 5622; // TO BE CHANGED WHEN ARV MEDICATION IS COMPLETE
        }
    }
    return $arvreginmen;
}

function getDrugGroup($csvColumn,$drugCoding){
    //$csvColumn[15]; // Drugname
    $dgroup = 165304;
    foreach($drugCoding as $line){
        if($csvColumn[17]=="NULL" || $csvColumn[17]=="0"  || $csvColumn[17]==""){
            $dgroup =  5622;
        }else if($line[2] != cleanDrug($csvColumn[17])){
            continue;
        }else if($line[2] == cleanDrug($csvColumn[17])){      
            $dgroup =  $line[13];
        break;
        }else {
            $dgroup =  165304;
        }
    }

    return $dgroup;
}

function DrugCategory($csvColumn){
    if($csvColumn[24]=="ARV Medication"){
        return 165724;
    }elseif($csvColumn[24]=="OI and Prophylaxis"){
        return 165727;
    }else{
        return 165304;
    }
}
function ARVMedication($csvColumn,$drugCoding){
    //$csvColumn[15]; // Drugname
    $arvm = 165724;
    foreach($drugCoding as $line){
        if($csvColumn[17]=="NULL" || $csvColumn[17]=="0"){
            $arvm = 5622;
        }else if($line[2] != cleanDrug($csvColumn[17])){
            continue;
        }else if($line[2] == cleanDrug($csvColumn[17])){      
            $arvm = $line[11];
        break;
        }else {
            $arvm = 165724; //  TO BE CHANGED LATER
        }
    }
    return $arvm;
}

function DrugStrengthConcept($csvColumn,$drugCoding){
    //$csvColumn[15]; // Drugname
    $strengthcode = 5622;
    foreach($drugCoding as $line){
        if($csvColumn[19]=="NULL" || $csvColumn[19]=="0"){
            $strengthcode = 5622;
        }else if($line[3] != $csvColumn[19]){
            continue;
        }else if($line[3]==$csvColumn[19]){      
            $strengthcode = $line[12];
        break;
        }else {
            $strengthcode = 5622; // (Other) TO BE CHANGED LATER
        }
    }

    
        return $strengthcode;
    
}

function getFrequency($csvColumn){
    switch($csvColumn){
        case 'PRN':
            return 165721;
        break;

        case 'OD':
            return 160862;
        break;

        case 'BD':
            return 160858;
        break;

        case '1BD':
            return 160858;
        break;

        default:
        return 5622;
    }
}
/*
function qtyPerDose($csvColumn){
    switch($csvColumn[31]){
        case 'PRN':
            return number_format($csvColumn[22]/$csvColumn[20],2);
        break;

        case 'OD':
            return number_format(($csvColumn[22]/$csvColumn[20])/1,2);
        break;

        case 'BD':
            return number_format(($csvColumn[22]/$csvColumn[20])/2,2);
        break;

        default:
        return 1;
    }
}
*/
/*
function getObsID($conn,$grow){
    $obs_id = $conn->query("SELECT obs_id FROM obs WHERE concept_id=$grow ORDER BY obs_id DESC LIMIT 1")->fetch_object()->obs_id;
    return $obs_id;
}
*/

function pharmacyConcepts($csvColumn,$firstVisit,$regimenCoding){
    // Used in populating pharmacy order form
    $pharmConcepts = array(
        // Treatment Type
        array(
            "conceptID" => 165945,
            "dataType" => "value_coded",
            "conceptAns" => getTreatmentType($csvColumn[18]),
            "csvcol" => ""
        ),
        //Type of Visit
        // Note* Some codes to check type of visit will be created
        array(
            "conceptID" => 164181,
            "dataType" => "value_coded",
            "conceptAns" => getVisitType($csvColumn),
            "csvcol" => ""
        ),
        // Pregnancy/Breast Feeding Status
        // Some Code needed
        array(
            "conceptID" => 165050,
            "dataType" => "value_coded",
            "conceptAns" => getPregnancyStatus($csvColumn),
            "csvcol" => ""
        ),
        // Pick up Reason
        array(
            "conceptID" => 165774,
            "dataType" => "value_coded",
            "conceptAns" => getPickupReason($csvColumn),
            "csvcol" => ""
        ),

        // get Adult or Child
        array(
            "conceptID" => 165720,
            "dataType" => "value_coded",
            "conceptAns" => getAgeGroup($csvColumn),
            "csvcol" => ""
        ),

        // Model of Care
        array(
            "conceptID" => 166148,
            "dataType" => "value_coded",
            "conceptAns" => 166153,
            "csvcol" => ""
        ),

        // Current Regiment Line
        array(
            "conceptID" => 165708,
            "dataType" => "value_coded",
            "conceptAns" => getRegimenLine($csvColumn),
            "csvcol" => ""
        ),

        // ARV Regimen **Under Construction *** takes care of adult or child regimen lines
        array(
            "conceptID" => getRegimenLine($csvColumn),
            "dataType" => "value_coded",
            "conceptAns" => getARVRegimen($csvColumn,$regimenCoding),
            "csvcol" => ""
        ),

        // Ordered Date
        array(
            "conceptID" => 164989,
            "dataType" => "value_datetime",
            "conceptAns" => "",
            "csvcol" => $csvColumn[5]
        ),

        // Ordered By
        array(
            "conceptID" => 164989,
            "dataType" => "value_text",
            "conceptAns" => "",
            "csvcol" => $csvColumn[6]
        )
        
      );

      return $pharmConcepts;
}

function pharmacyDrugs($csvColumn,$firstVisit,$drugCoding){
    // Used in populating pharmacy order form
    $pharmacyDrugs = array(
        /**********************************************/
         // Drug Grouping Concept
         array(
            "conceptID" => getDrugGroup($csvColumn,$drugCoding),
            "dataType" => "value_text",
            "conceptAns" => "", // Needs verification
            "csvcol" => null
        ),

        // ARV Medication
        array(
            "conceptID" => DrugCategory($csvColumn),
            "dataType" => "value_coded",
            "conceptAns" => ARVMedication($csvColumn,$drugCoding),
            "csvcol" => ""
        ),
       
        
        // Drug Strength Concept
        array(
            "conceptID" => 165725,
            "dataType" => "value_coded",
            "conceptAns" => DrugStrengthConcept($csvColumn,$drugCoding),
            "csvcol" => ""
        ),
        

         // Drug Dosage
         array(
            "conceptID" => 166120,
            "dataType" => "value_numeric",
            "conceptAns" => "",
            "csvcol" => oneNull($csvColumn[29])
        ),

        // Drug Frequency Concept
        array(
            "conceptID" => 165723,
            "dataType" => "value_coded",
            "conceptAns" => getFrequency($csvColumn[31]), // Once daily
            "csvcol" => ""
        ),

        // Drug Duration Concept
        array(
            "conceptID" => 159368,
            "dataType" => "value_numeric",
            "conceptAns" => "",
            "csvcol" => $csvColumn[20]
        ),

        // Drug Quantity of Medication Prescribed per dose
        array(
            "conceptID" => 160856,
            "dataType" => "value_numeric",
            "conceptAns" => "",
            "csvcol" => oneNull($csvColumn[30])
        ),

         

        // Drug Medication Dispensed
        array(
            "conceptID" => 1443,
            "dataType" => "value_numeric",
            "conceptAns" => "",
            "csvcol" => $csvColumn[22]
        ),
    
        /******************************************** */
      );

      return $pharmacyDrugs;
}


function trackingReason($csvColumn){
    if($csvColumn[23]=="Other"){
        return 5622;
    }else{
        return 165462;
    }
}

function ltfu($csvColumn){
    if($csvColumn[8]=="Lost to follow-up"){
        return 1066;
    }else{
        return 1065;
    }
}

function ltfuc($csvColumn){
    if($csvColumn[5]=="Lost to follow-up"){
        return 1066;
    }else{
        return 1065;
    }
}

function terminationReason($csvColumn){
    if($csvColumn[8]=="Lost to follow-up"){
        return 165916;
    }else if($csvColumn[8]=="Death"){
        return 165889;
    }else if($csvColumn[8]=="Transfer to another LPTF"){
        return 159492;
    }else{
        return 5622;
    }
}

function terminationReasonc($csvColumn){
    if($csvColumn[5]=="Lost to follow-up"){
        return 165916;
    }else if($csvColumn[5]=="Death"){
        return 165889;
    }else if($csvColumn[5]=="Transfer to another LPTF"){
        return 159492;
    }else{
        return 5622;
    }
}

function transferredTo($csvColumn){
    if($csvColumn[8]=="Transfer to another LPTF"){
        return $csvColumn[22];
    }else{
        return "";
    }
}

function causeOfDeath($csvColumn){
    if($csvColumn[8]=="Death"){
        return 1067;
    }else{
        return "";
    }
}

function cleanDrug($drug){
    $drug = str_replace(" ","",$drug);

    $drug = str_replace("+","/",$drug);

    if(strpos($drug,"(") !== false){
        $drug = substr($drug, 0, strpos($drug, "("));
    }
    return $drug;
}

function inactiveConcepts($csvColumn){
    // Inactive Patient List
    $inactiveConcepts = array(
        // Reason for Tracking
        array( 
            "conceptID" => 165460,
            "dataType" => "value_coded",
            "conceptAns" => trackingReason($csvColumn),
            "csvcol" => ""
        ),
        // Other Tracking Reason
        array(
            "conceptID" => 166138,
            "dataType" => "value_text",
            "conceptAns" => "",
            "csvcol" => "Not Reported"
        ),
        // Date of Last Actual Contact
        array(
            "conceptID" => 165461,
            "dataType" => "value_datetime",
            "conceptAns" => "",
            "csvcol" => date("Y-m-d", strtotime($csvColumn[16]))
        ),

        // Date of Missed Scheduled Appointment
        array(
            "conceptID" => 165778,
            "dataType" => "value_datetime",
            "conceptAns" => "",
            "csvcol" => date("Y-m-d", strtotime($csvColumn[16]))
        ),

        // NEED DATA FOR CONTACT ATTEMPTS

        // Lost to follow-up
        array(
            "conceptID" => 5240,
            "dataType" => "value_coded",
            "conceptAns" => ltfu($csvColumn),
            "csvcol" => ""
        ),

        // Reason for LTFU
        array(
            "conceptID" => 166157,
            "dataType" => "value_coded",
            "conceptAns" => 166154,
            "csvcol" => ""
        ),

        // Date of LTFU
        array(
            "conceptID" => 166152,
            "dataType" => "value_datetime",
            "conceptAns" => "",
            "csvcol" => date("Y-m-d", strtotime($csvColumn[16]))
        ),

        // Previuos ARV Exposure
        array(
            "conceptID" => 165586,
            "dataType" => "value_coded",
            "conceptAns" => 1065,
            "csvcol" => ""
        ),

        // Date of Termination
        array(
            "conceptID" => 165469,
            "dataType" => "value_datetime",
            "conceptAns" => "",
            "csvcol" => date("Y-m-d", strtotime($csvColumn[16]))
        ),

        // Reason for Termination
        array(
            "conceptID" => 165470,
            "dataType" => "value_coded",
            "conceptAns" => terminationReason($csvColumn),
            "csvcol" => ""
        ),

        // Transffered To
        array(
            "conceptID" => 159495,
            "dataType" => "value_text",
            "conceptAns" => "",
            "csvcol" => transferredTo($csvColumn)
        ),

        // Death
        array(
            "conceptID" => 165889,
            "dataType" => "value_coded",
            "conceptAns" => 1067, //causeOfDeath($csvColumn),
            "csvcol" => ""
        ),

        // Discontinued Care
        array(
            "conceptID" => 165916,
            "dataType" => "value_coded",
            "conceptAns" => 165890,
            "csvcol" => ""
        ),

        // Contact Tracker Signature Date
        array(
            "conceptID" => 165777,
            "dataType" => "value_datetime",
            "conceptAns" => "",
            "csvcol" => date("Y-m-d", strtotime($csvColumn[16]))
        )
        
      );

      return $inactiveConcepts;
}

function missedApp($csvColumn){
    if($csvColumn[2]!='NULL'){
        return date("Y-m-d", strtotime($csvColumn[2]));
    }else{
        return date("Y-m-d", strtotime($csvColumn[4]));
    }
}

function terminationConcepts($csvColumn){
    // Termination Patient List
    $terminationConcepts = array(
        // Reason for Tracking
        array( 
            "conceptID" => 165460,
            "dataType" => "value_coded",
            "conceptAns" => 5622,
            "csvcol" => ""
        ),
        // Other Tracking Reason
        array(
            "conceptID" => 166138,
            "dataType" => "value_text",
            "conceptAns" => "",
            "csvcol" => "Missed Appointment"
        ),
       
        // Date of Missed Scheduled Appointment
        array(
            "conceptID" => 165778,
            "dataType" => "value_datetime",
            "conceptAns" => "",
            "csvcol" => missedApp($csvColumn)
        ),

        // NEED DATA FOR CONTACT ATTEMPTS

        // Lost to follow-up
        array(
            "conceptID" => 5240,
            "dataType" => "value_text",
            "conceptAns" => ltfuc($csvColumn),
            "csvcol" => ""
        ),

        // Reason for LTFU
        array(
            "conceptID" => 166157,
            "dataType" => "value_coded",
            "conceptAns" => 166154,
            "csvcol" => ""
        ),

        // Date of LTFU
        array(
            "conceptID" => 166152,
            "dataType" => "value_datetime",
            "conceptAns" => "",
            "csvcol" => missedApp($csvColumn)
        ),

        // Previuos ARV Exposure
        array(
            "conceptID" => 165586,
            "dataType" => "value_coded",
            "conceptAns" => 1065,
            "csvcol" => ""
        ),

        // Date of Termination
        array(
            "conceptID" => 165469,
            "dataType" => "value_datetime",
            "conceptAns" => "",
            "csvcol" => date("Y-m-d", strtotime($csvColumn[4]))
        ),

        // Reason for Termination
        array(
            "conceptID" => 165470,
            "dataType" => "value_coded",
            "conceptAns" => terminationReasonc($csvColumn),
            "csvcol" => ""
        ),

        // Transffered To
        array(
            "conceptID" => 159495,
            "dataType" => "value_text",
            "conceptAns" => "",
            "csvcol" => ifNull($csvColumn[10])
        ),

        // Death
        array(
            "conceptID" => 165889,
            "dataType" => "value_coded",
            "conceptAns" => 1067,
            "csvcol" => ""
        ),

        // Discontinued Care
        array(
            "conceptID" => 165916,
            "dataType" => "value_coded",
            "conceptAns" => 5622,
            "csvcol" => ""
        ),

        // Contact Tracker Signature Date
        array(
            "conceptID" => 165777,
            "dataType" => "value_datetime",
            "conceptAns" => "",
            "csvcol" => date("Y-m-d", strtotime($csvColumn[4]))
        )
        
      );

      return $terminationConcepts;
}