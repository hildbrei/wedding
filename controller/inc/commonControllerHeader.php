<?php
/* 
 * Consider putting handy functions in commonAdapterHeader.php instead if they are useful by both controllers and adapters.
 */ 
ini_set('display_errors', 1);
error_reporting(~0);
session_start();
require_once dirname(__FILE__).'/../../adapter/userDataAdapter.php';


if (isNull($db)) {
	printJSonErrorAndExit();
}

function printJSonErrorAndExit() {
	$toEncode['Result'] = 'error';
	$toEncode['Messages'] = $_SESSION['errorMessages'];
	echo json_encode($toEncode);
	
	$_SESSION['errorMessages'] = array();
	exit;
}

function printJSonSuccessAndExit($successMessages, $data) {
	$toEncode['Result'] = 'success';
	$toEncode['Messages'] = $successMessages;
	foreach ($data as $key => $value) {
		$toEncode[$key] = $value;
	}
	echo json_encode($toEncode);
	exit;
}
?>