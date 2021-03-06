
SELECT CareEndedID
      ,mst_Patient.PatientEnrollmentID
      ,dtl_patientCareEnded.Ptn_Pk
      --,MissedAppDate
      ,CAST(MissedAppDate AS DATE)AS MissedAppDate
      ,CareEnded
      ,ARTended
      --,ARTenddate
      ,CAST(ARTenddate AS DATE)AS ARTEndDate
      ,ARTendreason
      --,PatientExitReason
      ,mst_Decode.Name AS PatientExitReason
      /*,CASE 
		WHEN mst_Decode.Name = 'Lost to follow-up' OR mst_Decode.Name = 'LTFU' THEN 'LTFU'
		WHEN mst_Decode.Name = 'Death' THEN 'Death'
		WHEN mst_Decode.Name = 'Transfer to another LPTF' THEN 'Transferred-Out'
		ELSE 'Stopped'
		END 
		AS PatientExitReason */
      ,DroppedOutReason
      ,DroppedOutReasonOther
      --,DeathDate
      ,CAST(DeathDate AS DATE)AS DeathDate      
      ,DeathReason
      ,DeathReasonDescription
      ,DataQuality
      ,dtl_PatientCareEnded.EmployeeID
      --,CareEndedDate
      ,CAST(CareEndedDate AS DATE)AS CareEndedDate 
      ,dtl_PatientCareEnded.UserID
      --,dtl_PatientCareEnded.CreateDate
      ,CAST(dtl_PatientCareEnded.CreateDate AS DATE)AS CreateDate 
      --,dtl_PatientCareEnded.UpdateDate
      ,CAST(dtl_PatientCareEnded.UpdateDate AS DATE)AS UpdateDate 
      ,dtl_PatientCareEnded.LocationId
      ,TrackingId
      --,LPTFTransfer
      ,mst_LPTF.Name AS LPTFTransfer
      --,FollowUpReason
      ,mst_LostFollowreason.Name AS FollowUpReason
      ,FollowUpReasonOther
      ,PMTCTCareEnded
      ,SuspectedSideEffect
      ,NonHiVCauses
      ,UnKnownDeath
      ,Sourceofdeathinformation
  FROM dtl_PatientCareEnded 
  LEFT JOIN mst_Decode ON mst_Decode.ID = dtl_PatientCareEnded.PatientExitReason
  LEFT JOIN mst_LostFollowreason ON mst_LostFollowreason.Id = dtl_PatientCareEnded.FollowUpReason
  LEFT JOIN mst_LPTF ON mst_LPTF.ID = dtl_PatientCareEnded.LPTFTransfer
  LEFT JOIN mst_Patient ON mst_Patient.Ptn_Pk = dtl_PatientCareEnded.Ptn_Pk
  WHERE mst_Patient.PatientEnrollmentID IS NOT NULL
  
  