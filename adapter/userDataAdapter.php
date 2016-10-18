<?php 
require_once dirname(__FILE__).'/inc/commonAdapterHeader.php'; // Gives $db connection and lots of other functions

function getUserDataFromUserCode($db, $UserCode) {
	$UserData = getUserDataIfValidUserCode($db, $UserCode);
	
	if (ifNotArrayWithElementsSetErrorMessageReturnTrue($UserData,"getUserDataFromUserCode: could not getUserDataIfValidUserCode()")) {
		return null;
	}
	
	return $UserData;
}

function getUserDataIfValidUserCode($db, $UserCode) {
	$UserCodeHash = sha1(trim(uppererCase($UserCode)));
	$UserCodeH = sha1(trim(uppererCase($UserCode))); // Had problem using same binding twice.
	$UserData = array();
	
	try {
		$stmt = $db->prepare('SELECT * FROM UserData WHERE UserCodeHash = :UserCodeHash AND UpdatedAtTime = ' .
		'(SELECT max(UpdatedAtTime) FROM UserData WHERE UserCodeHash = :UserCodeH) '.
		'LIMIT 1');

		$stmt->bindParam(':UserCodeHash', $UserCodeHash);
		$stmt->bindParam(':UserCodeH', $UserCodeH);
		$stmt->execute();
		$UserData = $stmt->fetch();
	} catch(PDOException $e) {
		setErrorMessage("getUserDataIfValidUserCode: SELECT * FROM UserData: database error: " . $e->getMessage());
		return null;
	}

	// Update controlCheck
	if (!$UserData) {
		setErrorMessage("getUserDataIfValidUserCode: UserData is false");
		return null;
	}
	
	return $UserData;
}


function addUserData($db, $UserData) {
	$UserDataFromDB = getUserDataIfValidUserCode($db, $UserData->UserCode);
	if (ifNullSetErrorMessageReturnTrue($UserDataFromDB, "addUserData: Kunne ikke finne bruken med UserCode:" . $UserData->UserCode)) {
		return null;
	}
	$UserDataFromDB = json_decode(json_encode($UserDataFromDB));
	
	// Return UserDataFromDB if no chanes has been made
	$userDataObjectsIsDifferent = userDataObjectsIsDifferent($UserDataFromDB, $UserData);
	if (ifNullSetErrorMessageReturnTrue($userDataObjectsIsDifferent, "addUserData: UserData inneholder ikke de rette attributtene.")) {
		return null;
	}
	if (!$userDataObjectsIsDifferent) {
		return getUserDataIfValidUserCode($db, $UserData->UserCode);
	}
	
	$nowTimestamp = getUnixNow();
	
	try {
		$query = 'INSERT INTO UserData ' .
			'(UserCodeHash, UserCode, UpdatedAtTime, FirstName, LastName, Email, Phone, LastFormPageChanged, ThursdayAccess, IsComing,' .
			'ArrivalSelected, DepartureSelected, CanSleepFromThu, CanSleepFromFri, SleepFromThuPrice, SleepFromFriPrice, SleepFromSatPrice, ' .
			'SleepSelected, RentSheet, CanEatThuDin, CanEatFriBre, CanEatFriDin, CanEatSatBre, CanEatSunBre, EatThuDinSelected,' .
			'EatFriBreSelected, EatFriDinSelected, EatSatBreSelected, EatSunBreSelected, AppetizerSelected, MainCourseSelected,' .
			'DessertSelected, Allergens, Comments, BringsDog)' .
			'VALUES ' .
			'(:UserCodeHash, :UserCode, :UpdatedAtTime, :FirstName, :LastName, :Email, :Phone, :LastFormPageChanged, :ThursdayAccess, :IsComing,' .
			':ArrivalSelected, :DepartureSelected, :CanSleepFromThu, :CanSleepFromFri, :SleepFromThuPrice, :SleepFromFriPrice, :SleepFromSatPrice, ' .
			':SleepSelected, :RentSheet, :CanEatThuDin, :CanEatFriBre, :CanEatFriDin, :CanEatSatBre, :CanEatSunBre, :EatThuDinSelected,' .
			':EatFriBreSelected, :EatFriDinSelected, :EatSatBreSelected, :EatSunBreSelected, :AppetizerSelected, :MainCourseSelected,' .
			':DessertSelected, :Allergens, :Comments, :BringsDog)';

		$stmt = $db->prepare($query);
		
		$stmt->bindParam(':UserCodeHash', $UserData->UserCodeHash);
		$stmt->bindParam(':UserCode', $UserData->UserCode);
		$stmt->bindParam(':UpdatedAtTime', $nowTimestamp);
		$stmt->bindParam(':FirstName', $UserData->FirstName);
		$stmt->bindParam(':LastName', $UserData->LastName);
		$stmt->bindParam(':Email', $UserData->Email);
		$stmt->bindParam(':Phone', $UserData->Phone);
		$stmt->bindParam(':LastFormPageChanged', $UserData->LastFormPageChanged);
		$stmt->bindParam(':ThursdayAccess', $UserData->ThursdayAccess);
		$stmt->bindParam(':IsComing', $UserData->IsComing);
		$stmt->bindParam(':ArrivalSelected', $UserData->ArrivalSelected);
		$stmt->bindParam(':DepartureSelected', $UserData->DepartureSelected);
		$stmt->bindParam(':CanSleepFromThu', $UserData->CanSleepFromThu);
		$stmt->bindParam(':CanSleepFromFri', $UserData->CanSleepFromFri);
		$stmt->bindParam(':SleepFromThuPrice', $UserData->SleepFromThuPrice);
		$stmt->bindParam(':SleepFromFriPrice', $UserData->SleepFromFriPrice);
		$stmt->bindParam(':SleepFromSatPrice', $UserData->SleepFromSatPrice);
		$stmt->bindParam(':SleepSelected', $UserData->SleepSelected);
		$stmt->bindParam(':RentSheet', $UserData->RentSheet);
		$stmt->bindParam(':CanEatThuDin', $UserData->CanEatThuDin);
		$stmt->bindParam(':CanEatFriBre', $UserData->CanEatFriBre);
		$stmt->bindParam(':CanEatFriDin', $UserData->CanEatFriDin);
		$stmt->bindParam(':CanEatSatBre', $UserData->CanEatSatBre);
		$stmt->bindParam(':CanEatSunBre', $UserData->CanEatSunBre);
		$stmt->bindParam(':EatThuDinSelected', $UserData->EatThuDinSelected);
		$stmt->bindParam(':EatFriBreSelected', $UserData->EatFriBreSelected);
		$stmt->bindParam(':EatFriDinSelected', $UserData->EatFriDinSelected);
		$stmt->bindParam(':EatSatBreSelected', $UserData->EatSatBreSelected);
		$stmt->bindParam(':EatSunBreSelected', $UserData->EatSunBreSelected);
		$stmt->bindParam(':AppetizerSelected', $UserData->AppetizerSelected);
		$stmt->bindParam(':MainCourseSelected', $UserData->MainCourseSelected);
		$stmt->bindParam(':DessertSelected', $UserData->DessertSelected);
		$stmt->bindParam(':Allergens', $UserData->Allergens);
		$stmt->bindParam(':Comments', $UserData->Comments);
		$stmt->bindParam(':BringsDog', $UserData->BringsDog);
			
		$stmt->execute();
	} catch(PDOException $e) {
		setErrorMessage("addUserData: INSERT INTO UserData : database error: " . $e->getMessage() . '. Query: ' . $query . '. UserData:' . json_encode($UserData));
		return null;
	}
	
	return getUserDataIfValidUserCode($db, $UserData->UserCode);
}

function userDataObjectsIsDifferent($AUserData, $BUserData) {
	if (!is_object($AUserData)) {setErrorMessage("userDataObjectsIsDifferent: First parameter is not an object (" . $AUserData . ")");return null;}
	if (!is_object($BUserData)) {setErrorMessage("userDataObjectsIsDifferent: Second parameter is not an object (" . $BUserData . ")");return null;}
	
	if (!property_exists($AUserData, 'UserCodeHash')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: UserCodeHash");return null;}
	if (!property_exists($BUserData, 'UserCodeHash')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: UserCodeHash");return null;}
	if (!property_exists($AUserData, 'UserCode')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: UserCode");return null;}
	if (!property_exists($BUserData, 'UserCode')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: UserCode");return null;}
	if (!property_exists($AUserData, 'UpdatedAtTime')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: UpdatedAtTime");return null;}
	if (!property_exists($BUserData, 'UpdatedAtTime')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: UpdatedAtTime");return null;}
	if (!property_exists($AUserData, 'FirstName')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: FirstName");return null;}
	if (!property_exists($BUserData, 'FirstName')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: FirstName");return null;}
	if (!property_exists($AUserData, 'LastName')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: LastName");return null;}
	if (!property_exists($BUserData, 'LastName')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: LastName");return null;}
	if (!property_exists($AUserData, 'Email')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: Email");return null;}
	if (!property_exists($BUserData, 'Email')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: Email");return null;}
	if (!property_exists($AUserData, 'Phone')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: Phone");return null;}
	if (!property_exists($BUserData, 'Phone')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: Phone");return null;}
	if (!property_exists($AUserData, 'LastFormPageChanged')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: LastFormPageChanged");return null;}
	if (!property_exists($BUserData, 'LastFormPageChanged')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: LastFormPageChanged");return null;}
	if (!property_exists($AUserData, 'ThursdayAccess')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: ThursdayAccess");return null;}
	if (!property_exists($BUserData, 'ThursdayAccess')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: ThursdayAccess");return null;}
	if (!property_exists($AUserData, 'IsComing')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: IsComing");return null;}
	if (!property_exists($BUserData, 'IsComing')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: IsComing");return null;}
	if (!property_exists($AUserData, 'ArrivalSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: ArrivalSelected");return null;}
	if (!property_exists($BUserData, 'ArrivalSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: ArrivalSelected");return null;}
	if (!property_exists($AUserData, 'DepartureSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: DepartureSelected");return null;}
	if (!property_exists($BUserData, 'DepartureSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: DepartureSelected");return null;}
	if (!property_exists($AUserData, 'CanSleepFromThu')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: CanSleepFromThu");return null;}
	if (!property_exists($BUserData, 'CanSleepFromThu')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: CanSleepFromThu");return null;}
	if (!property_exists($AUserData, 'CanSleepFromFri')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: CanSleepFromFri");return null;}
	if (!property_exists($BUserData, 'CanSleepFromFri')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: CanSleepFromFri");return null;}
	if (!property_exists($AUserData, 'SleepFromThuPrice')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: SleepFromThuPrice");return null;}
	if (!property_exists($BUserData, 'SleepFromThuPrice')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: SleepFromThuPrice");return null;}
	if (!property_exists($AUserData, 'SleepFromFriPrice')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: SleepFromFriPrice");return null;}
	if (!property_exists($BUserData, 'SleepFromFriPrice')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: SleepFromFriPrice");return null;}
	if (!property_exists($AUserData, 'SleepFromSatPrice')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: SleepFromSatPrice");return null;}
	if (!property_exists($BUserData, 'SleepFromSatPrice')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: SleepFromSatPrice");return null;}
	if (!property_exists($AUserData, 'SleepSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: SleepSelected");return null;}
	if (!property_exists($BUserData, 'SleepSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: SleepSelected");return null;}
	if (!property_exists($AUserData, 'RentSheet')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: RentSheet");return null;}
	if (!property_exists($BUserData, 'RentSheet')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: RentSheet");return null;}
	if (!property_exists($AUserData, 'CanEatThuDin')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: CanEatThuDin");return null;}
	if (!property_exists($BUserData, 'CanEatThuDin')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: CanEatThuDin");return null;}
	if (!property_exists($AUserData, 'CanEatFriBre')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: CanEatFriBre");return null;}
	if (!property_exists($BUserData, 'CanEatFriBre')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: CanEatFriBre");return null;}
	if (!property_exists($AUserData, 'CanEatFriDin')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: CanEatFriDin");return null;}
	if (!property_exists($BUserData, 'CanEatFriDin')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: CanEatFriDin");return null;}
	if (!property_exists($AUserData, 'CanEatSatBre')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: CanEatSatBre");return null;}
	if (!property_exists($BUserData, 'CanEatSatBre')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: CanEatSatBre");return null;}
	if (!property_exists($AUserData, 'CanEatSunBre')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: CanEatSunBre");return null;}
	if (!property_exists($BUserData, 'CanEatSunBre')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: CanEatSunBre");return null;}
	if (!property_exists($AUserData, 'EatThuDinSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: EatThuDinSelected");return null;}
	if (!property_exists($BUserData, 'EatThuDinSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: EatThuDinSelected");return null;}
	if (!property_exists($AUserData, 'EatFriBreSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: EatFriBreSelected");return null;}
	if (!property_exists($BUserData, 'EatFriBreSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: EatFriBreSelected");return null;}
	if (!property_exists($AUserData, 'EatFriDinSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: EatFriDinSelected");return null;}
	if (!property_exists($BUserData, 'EatFriDinSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: EatFriDinSelected");return null;}
	if (!property_exists($AUserData, 'EatSatBreSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: EatSatBreSelected");return null;}
	if (!property_exists($BUserData, 'EatSatBreSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: EatSatBreSelected");return null;}
	if (!property_exists($AUserData, 'EatSunBreSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: EatSunBreSelected");return null;}
	if (!property_exists($BUserData, 'EatSunBreSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: EatSunBreSelected");return null;}
	if (!property_exists($AUserData, 'AppetizerSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: AppetizerSelected");return null;}
	if (!property_exists($BUserData, 'AppetizerSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: AppetizerSelected");return null;}
	if (!property_exists($AUserData, 'MainCourseSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: MainCourseSelected");return null;}
	if (!property_exists($BUserData, 'MainCourseSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: MainCourseSelected");return null;}
	if (!property_exists($AUserData, 'DessertSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: DessertSelected");return null;}
	if (!property_exists($BUserData, 'DessertSelected')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: DessertSelected");return null;}
	if (!property_exists($AUserData, 'Allergens')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: Allergens");return null;}
	if (!property_exists($BUserData, 'Allergens')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: Allergens");return null;}
	if (!property_exists($AUserData, 'Comments')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: Comments");return null;}
	if (!property_exists($BUserData, 'Comments')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: Comments");return null;}
	if (!property_exists($AUserData, 'BringsDog')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: BringsDog");return null;}
	if (!property_exists($BUserData, 'BringsDog')) {setErrorMessage("userDataObjectsIsDifferent: Cannot find property: BringsDog");return null;}

	
	if (
		$AUserData->UserCodeHash != $BUserData->UserCodeHash ||
		$AUserData->UserCode != $BUserData->UserCode ||
		$AUserData->UpdatedAtTime != $BUserData->UpdatedAtTime ||
		$AUserData->FirstName != $BUserData->FirstName ||
		$AUserData->LastName != $BUserData->LastName ||
		$AUserData->Email != $BUserData->Email ||
		$AUserData->Phone != $BUserData->Phone ||
		$AUserData->LastFormPageChanged != $BUserData->LastFormPageChanged ||
		$AUserData->ThursdayAccess != $BUserData->ThursdayAccess ||
		$AUserData->IsComing != $BUserData->IsComing ||
		$AUserData->ArrivalSelected != $BUserData->ArrivalSelected ||
		$AUserData->DepartureSelected != $BUserData->DepartureSelected ||
		$AUserData->CanSleepFromThu != $BUserData->CanSleepFromThu ||
		$AUserData->CanSleepFromFri != $BUserData->CanSleepFromFri ||
		$AUserData->SleepFromThuPrice != $BUserData->SleepFromThuPrice ||
		$AUserData->SleepFromFriPrice != $BUserData->SleepFromFriPrice ||
		$AUserData->SleepFromSatPrice != $BUserData->SleepFromSatPrice ||
		$AUserData->SleepSelected != $BUserData->SleepSelected ||
		$AUserData->RentSheet != $BUserData->RentSheet ||
		$AUserData->CanEatThuDin != $BUserData->CanEatThuDin ||
		$AUserData->CanEatFriBre != $BUserData->CanEatFriBre ||
		$AUserData->CanEatFriDin != $BUserData->CanEatFriDin ||
		$AUserData->CanEatSatBre != $BUserData->CanEatSatBre ||
		$AUserData->CanEatSunBre != $BUserData->CanEatSunBre ||
		$AUserData->EatThuDinSelected != $BUserData->EatThuDinSelected ||
		$AUserData->EatFriBreSelected != $BUserData->EatFriBreSelected ||
		$AUserData->EatFriDinSelected != $BUserData->EatFriDinSelected ||
		$AUserData->EatSatBreSelected != $BUserData->EatSatBreSelected ||
		$AUserData->EatSunBreSelected != $BUserData->EatSunBreSelected ||
		$AUserData->AppetizerSelected != $BUserData->AppetizerSelected ||
		$AUserData->MainCourseSelected != $BUserData->MainCourseSelected ||
		$AUserData->DessertSelected != $BUserData->DessertSelected ||
		$AUserData->Allergens != $BUserData->Allergens ||
		$AUserData->Comments != $BUserData->Comments ||
		$AUserData->BringsDog != $BUserData->BringsDog
	) {
		return true;
	}
	return false;
}
?>