  DECLARE @RegimenType varchar
  DECLARE @RegimenId INT
  SET @RegimenId = NULL
  SET @RegimenType = NULL

  SELECT TOP 100 mst_Patient.PatientEnrollmentID
      ,VW_PatientPharmacyNonARV.Ptn_pk
      ,VisitID
      ,VW_PatientPharmacyNonARV.LocationID
      ,OrderedBy
      --,[OrderedByDate]
      ,CAST(OrderedByDate AS DATE)AS OrderedByDate
      --,[DispensedBy]
      ,mst_User.UserFirstName AS DispensedBy
      --,mst_user.UserLastName AS LastName
      --,CONCAT(mst_user.UserFirstName,mst_User.UserLastName)
      --,[DispensedByDate]
      ,CAST(DispensedByDate AS DATE)AS DispensedByDate
      --,[ProgID]
      ,mst_Decode.Name AS ProgID
      ,OrderType
      ,Height
      ,Weight
      ,ProviderID
      ,PharmacyPeriodTaken
      ,Drug_pk
      ,DrugName
      ,GenericID
      ,GenericName
      ,@RegimenType AS RegimenType
      ,@RegimenId AS RegimenId
      ,Duration
      ,OrderedQuantity
      ,DispensedQuantity
      ,Prophylaxis
      ,DrugType
      ,DrugTypeID
      --,[VisitDate]
      ,CAST(VisitDate AS DATE) AS VisitDate  
      --,[VisitType]
      ,VT.Name AS VisitType
      ,ptn_pharmacy_pk
  FROM VW_PatientPharmacyNonARV
  JOIN mst_Decode ON VW_PatientPharmacyNonARV.ProgID = mst_Decode.ID
  JOIN mst_Decode  VT ON VW_PatientPharmacyNonARV.ProgID = VT.ID
  JOIN mst_User ON VW_PatientPharmacyNonARV.DispensedBy = mst_User.UserID
  JOIN mst_Patient ON mst_Patient.Ptn_Pk = VW_PatientPharmacyNonARV.Ptn_Pk
  WHERE mst_Patient.PatientEnrollmentID IS NOT NULL
  
 
 