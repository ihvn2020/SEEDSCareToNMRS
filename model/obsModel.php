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