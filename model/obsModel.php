<?php

function obsValueType($clinicalCSV,$obsrow){

   foreach($clinicalCSV as $line){
      if($line[1] != $obsrow){
          continue;
      }else if($line[1] == $obsrow){      
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
            return "";
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
            return "";
    }
}

function getPMTCT($pmtct){
    if($pmtct!=NULL){
        return 1;
    }else{
        return 0;
    }
}

function hivEnrollentConcepts($csvColumn){
    $obsCols = array(
        // Date of HIV Enrollment
        array(
            "conceptID" => 160554,
            "dataType" => "value_datetime",
            "conceptAns" => "",
            "csvcol" => $csvColumn[26]
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
            "dataType" => "value_date",
            "conceptAns" => "",
            "csvcol" => $csvColumn[48]
        ),

        // ART Treatment Start Date
        array(
            "conceptID" => 159599,
            "dataType" => "value_date",
            "conceptAns" => "",
            "csvcol" => $csvColumn[26]
        ),

        // Marital/Civil Status
        array(
            "conceptID" => 1054,
            "dataType" => "value_coded",
            "conceptAns" => getCivilAns($csvColumn[13]),
            "csvcol" => $csvColumn[13]
        ),

        // Highest Education
        array(
            "conceptID" => 1712,
            "dataType" => "value_coded",
            "conceptAns" => getEduLevel($csvColumn[14]),
            "csvcol" => $csvColumn[14]
        ),

        // Currently pregnant on Enrollment
        array(
            "conceptID" => 1434,
            "dataType" => "value_coded",
            "conceptAns" => getPMTCT($csvColumn[41]),
            "csvcol" => $csvColumn[41]
        ),

        // Contact Phone Number
        array(
            "conceptID" => 159635,
            "dataType" => "value_text",
            "conceptAns" => "",
            "csvcol" => $csvColumn[3]
        )
        
      );

      return $obsCols;
}

function getTreatmentType($csvColumn){
    if($csvColumn=="ARV Medication"){
        return 165303;
    }else{
        return 165941;
    }
}

function getVisitType($csvColumn,$firstVisit){
    
        if(isset($firstVisit[$csvColumn[0]][0])){
            return 160530;
        }else{
            return 164180;
        }
}
function getPregnancyStatus($csvColumn,$firstVisit){    
    // Get First Visit Array   
														
    // $firstVisitTxt = file_get_contents('firstVisit.txt');													
    // $firstVisit = unserialize($firstVisitTxt);

    if(isset($firstVisit[$csvColumn[0]]) && $firstVisit[$csvColumn[0]][2]!='NULL'){
        return 165047;
    }else{
        return 165048;
    }
}

function getPickupReason($csvColumn,$firstVisit){
    
    if(isset($firstVisit[$csvColumn[0]][0])){
        return 165662;
    }else{
        return 165773;
    }
}

function getAgeGroup($csvColumn,$firstVisit){
    if(isset($firstVisit[$csvColumn[0]][3])){
        // echo $csvColumn[0];
        $firstVisit[$csvColumn[0]][3];

        $d1 = DateTime::createFromFormat('d/m/Y',$firstVisit[$csvColumn[0]][3]);
        $d2 = DateTime::createFromFormat('d/m/Y',$csvColumn[5]);

        $diff = $d2->diff($d1);

        $age = $diff->y;

        if($age<15){

            return 1528;
        }else{
            return 165709;
        }
    }
}

function getRegimenLine($csvColumn,$firstVisit){
    switch(getAgeGroup($csvColumn,$firstVisit)){
        case 1528: // Child
            if(substr($csvColumn[18], -2)=='/r'){
                return 164514; // Child Second Line
            }else{
                return 164507; // Child First Line
            }
        break;

        case 165709: // Adult
            if(substr($csvColumn[18], -2)=='/r'){
                return 164513; // Adult Second Line
            }else{
                return 164506; // Adult First Line
            }
        break;
    }
}

function getARVRegimen($csvColumn,$drugCoding){
    //$csvColumn[15]; // Drugname

    foreach($drugCoding as $line){
        if($line[2] != $csvColumn[15]){
            continue;
        }else if($line[3] == $csvColumn[15]){      
            return $line[11];
        break;
        }else {
            return "";
        }
    }

}

function ARVMedication($csvColumn,$drugCoding){
    //$csvColumn[15]; // Drugname

    foreach($drugCoding as $line){
        if($line[2] != $csvColumn[15]){
            continue;
        }else if($line[3] == $csvColumn[15]){      
            return $line[11];
        break;
        }else {
            return "";
        }
    }

}

function pharmacyConcepts($csvColumn,$firstVisit,$drugCoding){
    // Used in populating pharmacy order form
    $pharmConcepts = array(
        // Treatment Type
        array(
            "conceptID" => 165945,
            "dataType" => "value_coded",
            "conceptAns" => getTreatmentType($csvColumn[24]),
            "csvcol" => ""
        ),
        //Type of Visit
        // Note* Some codes to check type of visit will be created
        array(
            "conceptID" => 164181,
            "dataType" => "value_coded",
            "conceptAns" => getVisitType($csvColumn,$firstVisit),
            "csvcol" => ""
        ),
        // Pregnancy/Breast Feeding Status
        // Some Code needed
        array(
            "conceptID" => 165050,
            "dataType" => "value_coded",
            "conceptAns" => getPregnancyStatus($csvColumn,$firstVisit),
            "csvcol" => ""
        ),
        // Pick up Reason
        array(
            "conceptID" => 165774,
            "dataType" => "value_coded",
            "conceptAns" => getPickupReason($csvColumn,$firstVisit),
            "csvcol" => ""
        ),

        // get Adult or Child
        array(
            "conceptID" => 165720,
            "dataType" => "value_coded",
            "conceptAns" => getAgeGroup($csvColumn,$firstVisit),
            "csvcol" => ""
        ),

        // Current Regiment Line
        array(
            "conceptID" => 165708,
            "dataType" => "value_coded",
            "conceptAns" => getRegimenLine($csvColumn,$firstVisit),
            "csvcol" => ""
        ),

        // ARV Regimen **Under Construction ***
        array(
            "conceptID" => getRegimenLine($csvColumn,$firstVisit),
            "dataType" => "value_coded",
            "conceptAns" => getARVRegimen($csvColumn,$drugCoding),
            "csvcol" => ""
        ),

         // ARV Medication
         array(
            "conceptID" => getRegimenLine($csvColumn,$firstVisit),
            "dataType" => "value_coded",
            "conceptAns" => ARVMedication($csvColumn,$drugCoding),
            "csvcol" => ""
        ),

        // Ordered Date
        array(
            "conceptID" => 164989,
            "dataType" => "value_datetime",
            "conceptAns" => $csvColumn[5],
            "csvcol" => ""
        )
        
      );

      return $pharmConcepts;
}