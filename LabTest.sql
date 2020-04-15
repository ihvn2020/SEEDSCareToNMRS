
SELECT DISTINCT VW_PatientLaboratory.LabID
	  ,ord_visit.Visit_Id
      ,mst_Patient.PatientEnrollmentID 
      ,VW_PatientLaboratory.Ptn_pk
      ,VW_PatientLaboratory.LocationID
      --,OrderedbyName
      ,mst_Employee.FirstName AS OrderedByName
      --,mst_Employee.LastName
      --,OrderedbyDate
      ,CAST(VW_PatientLaboratory.OrderedbyDate AS DATE)AS OrderedbyDate
      --,ReportedbyName
      ,R.FirstName AS ReportedByName
      --,ReportedbyDate
      ,CAST(VW_PatientLaboratory.ReportedbyDate AS DATE)AS ReportedbyDate
      --,CheckedbyName
      ,C.FirstName AS CheckedByName
      --,CheckedbyDate
      ,CAST(VW_PatientLaboratory.CheckedbyDate AS DATE)AS CheckedbyDate
      --,PreClinicLabDate
      ,CAST(VW_PatientLaboratory.PreClinicLabDate AS DATE)AS PreClinicLabDate     
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
      ,CAST(VW_PatientLaboratory.VisitDate AS DATE)AS VisitDate
      ,VW_PatientLaboratory.LabPeriod
  FROM VW_PatientLaboratory
  LEFT JOIN mst_Patient ON mst_Patient.Ptn_Pk = VW_PatientLaboratory.Ptn_pk
  LEFT JOIN mst_Employee ON VW_PatientLaboratory.OrderedbyName = mst_Employee.EmployeeID
  LEFT JOIN mst_Employee R ON VW_PatientLaboratory.ReportedbyName = R.EmployeeID
  LEFT JOIN mst_Employee C ON VW_PatientLaboratory.ReportedbyName = C.EmployeeID
  LEFT JOIN dtl_PatientLabResults ON dtl_PatientLabResults.LabTestID =  VW_PatientLaboratory.[Test GroupId]
  LEFT JOIN mst_Decode ON dtl_PatientLabResults.Units = mst_Decode.ID
  LEFT JOIN ord_PatientLabOrder ON ord_PatientLabOrder.LabID = VW_PatientLaboratory.LabID
  LEFT JOIN ord_Visit ON ord_Visit.Visit_Id = ord_PatientLabOrder.VisitId
  WHERE mst_Patient.PatientEnrollmentID IS NOT NULL
  
 