USE SEEDSCare
GO 

Open symmetric key Key_CTC decryption by password='ttwbvXWpqb5WOLfLrBgisw=='

	  
	  SELECT  TOP 200 
	   PatientEnrollmentID
      ,LocationID
      ,Ptn_Pk
      ,PatientClinicID
      ,ReferredFrom
      ,CAST(RegistrationDate AS DATE)AS RegistrationDate
      ,mst_Decode.Name AS Sex
      ,CAST(DOB AS DATE)AS DOB
      ,DobPrecision
      ,LocalCouncil
      ,VillageName
      ,DistrictName
      ,Province
      ,D.Name AS MaritalStatus
      ,EducationLevel
      ,EducationOther
      ,Literacy
      ,EmployeeID
      ,CASE Status
		WHEN 0 THEN 'Active'
		WHEN 1 THEN 'InActive'
		ELSE NULL
	  END AS Status
      ,CAST(StatusChangedDate AS DATE) AS StatusChangedDate
      ,ProgramID
      ,Notes
      ,mst_Patient.DeleteFlag
      ,mst_Patient.UserID
      ,CAST(mst_Patient.CreateDate AS DATE) AS CreateDate      
      ,CAST(mst_Patient.UpdateDate AS DATE) AS UpdateDate      
      ,CAST(ARTStartDate AS DATE) AS ARTStartDate
      ,CountryId
      ,PosId
      ,SatelliteId
      ,UPPER(convert(varchar(50), decryptbykey([MiddleName]))) AS Middlename
      ,[Division]
      ,[Ward]
      ,UPPER(convert(varchar(50), decryptbykey([FirstName]))) AS Firstname
      ,UPPER(convert(varchar(50), decryptbykey([LastName]))) AS Lastname
      ,UPPER(convert(varchar(50), decryptbykey([Address]))) AS Address
      ,UPPER(convert(varchar(50), decryptbykey([Phone]))) AS Phone
      ,ReferredFromSpecify
      ,TransferIn
      ,LPTFTransferId
      ,ANCNumber
      ,PMTCTNumber
      ,AdmissionNumber
      ,OutpatientNumber
      ,IQNumber
      ,HealthUnit
      ,SubCountry
      ,whose
      ,CAST(DateTransferredin AS DATE)AS DateTransferredin
      ,NearestSchool
      ,NearestHealthCentre
  FROM mst_Patient
  LEFT JOIN mst_Decode ON mst_Decode.ID = mst_Patient.Sex 
  LEFT JOIN mst_Decode D ON D.ID = mst_Patient.MaritalStatus
 
 
 