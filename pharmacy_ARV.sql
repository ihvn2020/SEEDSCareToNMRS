
SELECT DISTINCT mst_Patient.PatientEnrollmentID
	  ,VW_PatientPharmacy.Ptn_pk
      ,VisitID
      ,VW_PatientPharmacy.LocationID
      ,OrderedBy
      --,[OrderedByDate]
      ,CAST(VW_PatientPharmacy.OrderedByDate AS DATE)AS OrderedByDate
      --,[DispensedBy]
      ,mst_User.UserFirstName AS DispensedBy
      --,mst_user.UserLastName AS LastName
      --,CONCAT(mst_user.UserFirstName,mst_User.UserLastName)
      --,[DispensedByDate]
      ,CAST(VW_PatientPharmacy.DispensedByDate AS DATE)AS DispensedByDate
      --,[ProgID]
      ,mst_Decode.Name AS ProgID
      ,OrderType
      ,Height
      ,Weight
      ,ProviderID
      ,PharmacyPeriodTaken
      ,Drug_pk
      ,DrugName
      ,VW_PatientPharmacy.GenericID
      ,GenericName
      ,RegimenType
      ,mst_Strength.StrengthName
      ,RegimenId 
      ,Duration
      ,OrderedQuantity
      ,DispensedQuantity
      ,Prophylaxis
      --,DrugType
      --,DrugTypeID
      --,[VisitDate]
      ,CAST(VW_PatientPharmacy.VisitDate AS DATE) AS VisitDate  
      --,[VisitType]
      ,VT.Name AS VisitType
      ,ptn_pharmacy_pk
  FROM VW_PatientPharmacy
  JOIN mst_Decode ON VW_PatientPharmacy.ProgID = mst_Decode.ID
  JOIN mst_Decode  VT ON VW_PatientPharmacy.ProgID = VT.ID
  JOIN mst_User ON VW_PatientPharmacy.DispensedBy = mst_User.UserID
  JOIN mst_Patient ON mst_Patient.Ptn_Pk = VW_PatientPharmacy.Ptn_pk
  JOIN lnk_DrugStrength ON VW_PatientPharmacy.GenericID = lnk_DrugStrength.GenericID
  JOIN mst_Strength ON lnk_DrugStrength.StrengthId = mst_Strength.StrengthId
  WHERE PatientEnrollmentID IS NOT NULL