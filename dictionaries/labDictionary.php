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

    // Get Concept ID of current column or variable name
    function getCID($labCSV,$variablePosition){           
            foreach($labCSV as $line){
                if($line[0] != $variablePosition){
                    continue;
                }else if($line[0] == $variablePosition){
                    return $line[2];
                }else{
                    return "";
                }
            }
    }

    // Get Any type of Answers
    function getAns($labCSV,$variablePosition,$rawAnswer){
        foreach($labCSV as $line){
            if($line[0] != $variablePosition){
                continue;
            }else if($line[0]==$variablePosition && $line[3]=="value_numeric"){           
                return $rawAnswer; // Or $line[7];
            }else if($line[0] == $variablePosition && $line[3]=="value_coded"){                 
                if($line[4]==$rawAnswer){                    
                    return $line[5];
                }  
            }else{
                return "";
            }
        }
    }
    
    // Get ConceptID Answers for value Coded Answers
    function getCIDAns($variableName,$rawAnswer){

        foreach($labCSV as $line){
            if($line[1] != $variableName){
                continue;
            }else if($line[1] == $variableName && $line[4]==$rawAnswer){
                return $line[5];
            }else{
                return "";
            }
        }
    }

    // Get ConceptID Answers for value Coded Answers
    function getNumericAns($variableName){

        foreach($labCSV as $line){
            if($line[1] != $variableName){
                continue;
            }else if($line[1] == $variableName){           
                return $line[5];
            }else{
                return "";
            }
        }
    }    

}

