<?php
    
class clinicalDictionary{
    
    // Get the Clinic CSV Record and Store in variable;
    public function clinicalCSV(){        
        $clinicalCSV = array_map('str_getcsv', file('/assets/resources/clinicalcsv.csv'));
        return $clinicalCSV;
    }

    // Get Concept ID of current column or variable name
    function getCID($variableName){
            //Get the Clinical CSV Data
            // $clinicalCSV = $clinicalCSV();
            
            foreach(clinicalCSV() as $line){
                if($line[0] != $variableName){
                    continue;
                }else if($line[0] == $variableName){
                break;
                    return $line[4];
                }else{
                    return "";
                }
            }
    }
    /* Fields that needs definition

        Ptn_pk/nLocationID
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
    function getCIDAns($variableName,$rawAnswer){

        foreach(clinicalCSV() as $line){
            if($line[0] != $variableName){
                continue;
            }else if($line[0] == $variableName && $line[4]==$rawAnswer){
            break;
                return $line[5];
            }else{
                return "";
            }
        }
    }
    

}

