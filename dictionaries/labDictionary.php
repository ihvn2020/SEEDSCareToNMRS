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

class labDictionary{
    
    /* Get the Clinic CSV Record and Store in variable;
    public function clinicalCSV(){        
        $clinicalCSV = array_map('str_getcsv', file('/assets/resources/clinicalcsv.csv'));
        return $clinicalCSV;
    }
    */

    // Get Concept ID of current column or variable name
    function getCID($labCSV,$variablePosition){
            //Get the Clinical CSV Data
            // $clinicalCSV = $clinicalCSV();
            
            foreach($clinicalCSV as $line){
                if($line[1] != $variablePosition){
                    continue;
                }else if($line[1] == $variablePosition){
                break;
                    return $line[6];
                }else{
                    return "";
                }
            }
    }

    // Get ConceptID Answers for value Coded Answers
    function getAns($clinicalCSV,$variablePosition,$rawAnswer){

        foreach($clinicalCSV as $line){
            if($line[1] != $variablePosition){
                continue;
            }else if($line[1] == $variablePosition && $line[4]=="numeric"){
            break;
                return $csvColumn[$variablePosition];
            }else if($line[1] == $variablePosition && $line[5]==$rawAnswer){
            break;
                return $line[6];
            }else{
                return "";
            }
        }
    }
    
    // Get ConceptID Answers for value Coded Answers
    function getCIDAns($variableName,$rawAnswer){

        foreach($clinicalCSV as $line){
            if($line[1] != $variableName){
                continue;
            }else if($line[1] == $variableName && $line[4]==$rawAnswer){
            break;
                return $line[5];
            }else{
                return "";
            }
        }
    }

    // Get ConceptID Answers for value Coded Answers
    function getNumericAns($variableName){

        foreach($clinicalCSV as $line){
            if($line[1] != $variableName){
                continue;
            }else if($line[1] == $variableName){
            break;
                return $line[5];
            }else{
                return "";
            }
        }
    }    

}

