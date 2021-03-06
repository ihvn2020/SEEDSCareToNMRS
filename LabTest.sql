
SELECT DISTINCT VW_PatientLaboratory.LabID
      ,mst_Patient.PatientEnrollmentID 
      ,VW_PatientLaboratory.Ptn_pk
      ,VW_PatientLaboratory.LocationID
      --,OrderedbyName
      ,mst_Employee.FirstName AS OrderedByName
      --,mst_Employee.LastName
      --,OrderedbyDate
      ,CAST(OrderedbyDate AS DATE)AS OrderedbyDate
      --,ReportedbyName
      ,R.FirstName AS ReportedByName
      --,ReportedbyDate
      ,CAST(ReportedbyDate AS DATE)AS ReportedbyDate
      --,CheckedbyName
      ,C.FirstName AS CheckedByName
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
      ,VW_PatientLaboratory.TestResults
      --,VW_PatientLaboratory.TestResults1
      --,dtl_PatientLabResults.Units
      ,mst_Decode.Name AS Units
      --,VW_PatientLaboratory.TestResultId
      ,[Parameter Result]
      --,VisitDate
      ,CAST(VisitDate AS DATE)AS VisitDate
      ,LabPeriod
  FROM VW_PatientLaboratory
  LEFT JOIN mst_Patient ON mst_Patient.Ptn_Pk = VW_PatientLaboratory.Ptn_pk
  LEFT JOIN mst_Employee ON VW_PatientLaboratory.OrderedbyName = mst_Employee.EmployeeID
  LEFT JOIN mst_Employee R ON VW_PatientLaboratory.ReportedbyName = R.EmployeeID
  LEFT JOIN mst_Employee C ON VW_PatientLaboratory.ReportedbyName = C.EmployeeID
  LEFT JOIN dtl_PatientLabResults ON dtl_PatientLabResults.LabTestID =  VW_PatientLaboratory.[Test GroupId]
  LEFT JOIN mst_Decode ON dtl_PatientLabResults.Units = mst_Decode.ID
  WHERE mst_Patient.PatientEnrollmentID IS NOT NULL
 