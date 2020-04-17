--UPDATE VW_PatientCareEnd SET CareEnded = 1 WHERE CareEnded = 0
--GO
SELECT mst_Patient.PatientEnrollmentID AS EnrollmentID
	  ,mst_patient.PatientClinicID AS HospitalID
      --,MissedAppDate
      --,convert(varchar, MissedAppDate, 101) AS MissedAppDate
      ,CAST(MissedAppDate AS DATE)AS MissedAppDate
      ,CareEnded
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
  FROM VW_PatientCareEnd
  LEFT JOIN mst_Patient ON mst_Patient.Ptn_Pk = vw_PatientCareEnd.Ptn_Pk
  WHERE mst_patient.PatientEnrollmentID IS NOT NULL

 