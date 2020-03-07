
SELECT LabID
      ,Ptn_pk
      ,LocationID
      ,OrderedbyName
      --,OrderedbyDate
      ,CAST(OrderedbyDate AS DATE)AS OrderedbyDate
      ,ReportedbyName
      --,ReportedbyDate
      ,CAST(ReportedbyDate AS DATE)AS ReportedbyDate
      ,CheckedbyName
      --,CheckedbyDate
      ,CAST(ReportedbyDate AS DATE)AS ReportedbyDate
      --,PreClinicLabDate
      ,CAST(PreClinicLabDate AS DATE)AS PreClinicLabDate     
      ,TestName
      ,TestID
      ,[Test GroupId]
      ,[Test GroupName]
      ,LabDepartmentID
      ,LabDepartmentName
      ,LabTypeID
      ,LabTypeName
      ,TestResults
      ,TestResults1
      ,TestResultId
      ,[Parameter Result]
      --,VisitDate
      ,CAST(VisitDate AS DATE)AS VisitDate
      ,LabPeriod
  FROM VW_PatientLaboratory