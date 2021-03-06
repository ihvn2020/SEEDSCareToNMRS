
SELECT DISTINCT mst_Patient.PatientEnrollmentID 
      ,dtl_PatientVitals.Ptn_pk
      ,dtl_PatientVitals.LocationID
      ,dtl_PatientVitals.Visit_pk
      ,Temp
      ,RR
      ,HR
      ,BPDiastolic
      ,BPSystolic
      ,Height
      ,Weight
      ,Pain
      ,dbo.dtl_PatientVitals.UserID
      ,CAST(dbo.dtl_PatientVitals.CreateDate AS DATE)AS CreateDate
      ,CAST(dbo.dtl_PatientVitals.UpdateDate AS DATE)AS UpdateDate
      ,TLC
      ,TLCPercent
      ,Oedema
      ,Pulse
      ,HeadCircumference
      ,MUAC
      ,SurfaceArea
      ,AdditionalFindings
      --,WABStage
      ,mst_Decode.Name AS WABStage
      --,WHOStage
      ,D.Name AS WHOStage
      --,TBStatus
      ,TB.Name AS TBStatus
      --,STIStatus
      ,DD.Name AS STIStatus
  FROM dbo.dtl_PatientVitals  
  LEFT JOIN dtl_PatientStage ON dtl_PatientVitals.Visit_pk = dtl_PatientStage.Visit_Pk
  LEFT JOIN mst_Decode ON mst_Decode.ID = dtl_PatientStage.WABStage
  LEFT JOIN mst_Decode D ON D.ID = dtl_PatientStage.WHOStage
  LEFT JOIN mst_Decode DD ON DD.ID = dtl_PatientStage.STIStatus
  LEFT JOIN mst_Decode TB ON TB.ID = dtl_PatientStage.TBStatus
  LEFT JOIN mst_Patient ON mst_Patient.Ptn_Pk = dtl_PatientVitals.Ptn_pk
  WHERE mst_Patient.PatientEnrollmentID IS NOT NULL
