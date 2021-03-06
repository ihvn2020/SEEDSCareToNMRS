SELECT TOP 100 mst_Patient.PatientEnrollmentID 
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
  LEFT JOIN dtl_PatientStage ON dtl_PatientVitals.Ptn_pk = dtl_PatientStage.Ptn_pk
  LEFT JOIN mst_Decode ON mst_Decode.ID = dtl_PatientStage.WABStage
  LEFT JOIN mst_Decode D ON D.ID = dtl_PatientStage.WHOStage
  LEFT JOIN mst_Decode DD ON DD.ID = dtl_PatientStage.STIStatus
  LEFT JOIN mst_Decode TB ON TB.ID = dtl_PatientStage.TBStatus
  LEFT JOIN mst_Patient ON mst_Patient.Ptn_Pk = dtl_PatientVitals.Ptn_pk

GROUP BY dtl_PatientVitals.Ptn_pk, mst_Patient.PatientEnrollmentID, dtl_PatientVitals.LocationID, dtl_PatientVitals.Visit_pk, dtl_PatientVitals.Temp, dtl_PatientVitals.RR, dtl_PatientVitals.HR, dtl_PatientVitals.BPDiastolic,
dtl_PatientVitals.BPSystolic, dtl_PatientVitals.Height, dtl_PatientVitals.Weight, dtl_PatientVitals.Pain, dtl_PatientVitals.UserID, dtl_PatientVitals.CreateDate, dtl_PatientVitals.UpdateDate, dtl_PatientVitals.TLC, dtl_PatientVitals.TLCPercent, dtl_PatientVitals.Oedema, dtl_PatientVitals.Pulse,
dtl_PatientVitals.HeadCircumference,dtl_PatientVitals.MUAC, dtl_PatientVitals.SurfaceArea, dtl_PatientVitals.AdditionalFindings, mst_Decode.Name, d.Name, dd.Name, tb.Name   

HAVING mst_Patient.PatientEnrollmentID IS NOT NULL