<?php
require_once dirname(__FILE__).'/inc/commonControllerHeader.php';


//$UserData = getUserDataFromUserCode($db, $_POST['UserCode']);	
$UserData = json_decode(file_get_contents("php://input"));

if (!isset($UserData) || !property_exists($UserData, 'UserCode')) {
	if (ifNotArrayWithElementsSetErrorMessageReturnTrue(null, 'Kunne ikke logge inn. (login.php: UserCode blir ikke postet som et JSON-attributt.)')) {
		printJSonErrorAndExit();
	}
}

$UserData = getUserDataFromUserCode($db, $UserData->UserCode);

if (ifNotArrayWithElementsSetErrorMessageReturnTrue($UserData, 'Kunne ikke logge inn. Har du tastet riktig ord? (login.php: Kan ikke hente UserData.)')) {
	printJSonErrorAndExit();
}

printJSonSuccessAndExit(array(), array("UserData" => $UserData));


?>