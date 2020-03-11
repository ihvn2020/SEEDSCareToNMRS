<?php

function obsValueType($csvColumn,$obsrow){

   foreach($clinicalCSV as $line){
      if($line[1] != $obsrow){
          continue;
      }else if($line[1] == $obsrow){
      break;
          return $line[4];
      }else {
          return "value_text";
      }
  }

   if($csvColumn[$obsrow]){

   };
}

//List of OBS Columns that will be impacted by Seedcare Data
$obscolumns = 
"person_id,
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