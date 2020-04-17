
SELECT mst_Patient.PatientEnrollmentID AS EnrollmentID
	  ,mst_patient.PatientClinicID AS HospitalID
      --,MissedAppDate
      --,convert(varchar, MissedAppDate, 101) AS MissedAppDate
      ,CAST(MissedAppDate AS DATE)AS MissedAppDate
      --,CareEnded
      ,CASE 
		WHEN CareEnded = 0 THEN 'No'
		ELSE 'Yes'
		END AS CareEnded
      ,CAST(CareEndedDate AS DATE)AS CareEndedDate
      ,[Patient CareEnd Reason] AS PatientExitReason
      ,CAST(DeathDate AS DATE)AS DeathDate
      ,[DeathReason]
      ,[Patient Death Reason]
      ,[Patient Death Reason Other]
      ,[LPTF Patient Transfered To] AS LPTFTransfer
      ,ARTended
      --,ARTenddate
      ,CAST(ARTenddate AS DATE)AS ARTenddate
      ,[ART End Reason]
      ,[ARTendreason]
      --,[PatientExitReason]
      ,[DroppedOutReason]
      ,[Patient Stopped Reason]
      ,[Patient Stopped Reason Other]
      --,[DeathDate]
      --,[LPTFTransfer]
      --,[PMTCTCareEnded]
      --,[CareEndedID]
      --,[CareEndedDate]
      --,[ModuleId]
        ,CAST(dtl_PatientCareRestarted.DateRestarted AS DATE)AS DateReturnToCare	
  FROM VW_PatientCareEnd
  LEFT JOIN mst_Patient ON mst_Patient.Ptn_Pk = vw_PatientCareEnd.Ptn_Pk
  LEFT JOIN dtl_PatientCareRestarted ON dtl_PatientCareRestarted.CareEndedID = VW_PatientCareEnd.CareEndedID
  WHERE mst_patient.PatientEnrollmentID IS NOT NULL

 