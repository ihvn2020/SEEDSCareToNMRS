
SELECT TOP 100 dbo.dtl_PatientVitals.Ptn_pk
      ,dbo.dtl_PatientVitals.LocationID
      ,dbo.dtl_PatientVitals.Visit_pk
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
  LEFT JOIN dbo.dtl_PatientStage ON dbo.dtl_PatientVitals.Ptn_pk = dbo.dtl_PatientStage.Ptn_pk
  LEFT JOIN mst_Decode ON mst_Decode.ID = dtl_PatientStage.WABStage
  LEFT JOIN mst_Decode D ON D.ID = dtl_PatientStage.WHOStage
  LEFT JOIN mst_Decode DD ON DD.ID = dtl_PatientStage.STIStatus
  LEFT JOIN mst_Decode TB ON TB.ID = dtl_PatientStage.TBStatus
  
  
  select * from dtl_PatientStage where Ptn_pk = 3368
  
  select * from dtl_PatientVitals where Ptn_pk = 3368
  
  select * from mst_Decode where ID = 84 or ID = 87