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