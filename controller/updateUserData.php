<?php
require_once dirname(__FILE__).'/inc/commonControllerHeader.php';


$UserData = json_decode(file_get_contents("php://input"));

if (!isset($UserData) || !property_exists($UserData, 'UserCode')) {
	if (ifNotArrayWithElementsSetErrorMessageReturnTrue(null, 'Kunne ikke oppdatere oppdatere svarskjemaet. (updateUserData.php: UserCode blir ikke postet som et JSON-attributt.)')) {
		printJSonErrorAndExit();
	}
}


$UserData = addUserData($db, $UserData);

if (ifNotArrayWithElementsSetErrorMessageReturnTrue($UserData, 'Kunne ikke oppdatere oppdatere svarskjemaet. (updateUserData.php)')) {
	printJSonErrorAndExit();
}

printJSonSuccessAndExit(array(), array("UserData" => $UserData));

?>