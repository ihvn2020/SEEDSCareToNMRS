<?php

    /* Fields that needs definition which maps to Concept Dictionary

        Ptn_pk
        LocationID
        Visit_pk
        -Temp
        -RR
        -HR
        -BPDiastolic
        -BPSystolic
        -Height
        -Weight
        -Pain
        UserID
        CreateDate
        UpdateDate
        -TLC
        -TLCPercent
        -Oedema
        -Pulse
        -HeadCircumference
        -MUAC
        -SurfaceArea
        -AdditionalFindings
        -WABStage
        -WHOStage
        -TBStatus
        -STIStatus
    */

class clinicalDictionary{
    
    /* Get the Clinic CSV Record and Store in variable;
    public function clinicalCSV(){        
        $clinicalCSV = array_map('str_getcsv', file('/assets/resources/clinicalcsv.csv'));
        return $clinicalCSV;
    }
    */

    // Get Concept ID of current column or variable name
    function getCID($clinicalCSV,$variablePosition){
            //Get the Clinical CSV Data
            // $clinicalCSV = $clinicalCSV();
            
            foreach($clinicalCSV as $line){
                if($line[2] != $variablePosition){
                    continue;
                }else if($line[2] == $variablePosition){
                    return $line[5];
                }else{
                    return "";
                }
            }
    }

    // Get Any type of Answers
    function getAns($clinicalCSV,$variablePosition,$rawAnswer){

        foreach($clinicalCSV as $line){
            if($line[2] != $variablePosition){
                continue;
            }else if($line[2] == $variablePosition && $line[3]=="value_numeric"){           
                return $rawAnswer;
            }else if($line[2] == $variablePosition && $line[4]==$rawAnswer){            
                return $line[6];
            }else{
                return "";
            }
        }
    }
    
    // Get ConceptID Answers for value Coded Answers
    function getCIDAns($variableName,$rawAnswer){

        foreach($clinicalCSV as $line){
            if($line[2] != $variableName){
                continue;
            }else if($line[2] == $variableName && $line[4]==$rawAnswer){
                return $line[5];
            }else{
                return "";
            }
        }
    }

    // Get ConceptID Answers for value Coded Answers
    function getNumericAns($variableName){

        foreach($clinicalCSV as $line){
            if($line[2] != $variableName){
                continue;
            }else if($line[2] == $variableName){           
                return $line[5];
            }else{
                return "";
            }
        }
    }    

}

