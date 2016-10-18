<?php 
ini_set('display_errors', 1);
error_reporting(~0);

require_once dirname(__FILE__).'/../../config/db.php';
require_once dirname(__FILE__).'/../../config/Config.php';

$db = (new Db())->getDbConnection();


/*
 *  Primitive variable-checking-functions
 */

function isNull($mayBeNull) {
	return is_null($mayBeNull);
}

function isInteger($mayBeInteger) {
	return is_int($mayBeInteger);
}

function isEmptyString($mayBeEmptyString) {
	return ($mayBeEmptyString === '');
}

function issetAndNotEmpty($mayBeEmptyString) {
	return isset($mayBeEmptyString) && !isEmptyString($mayBeEmptyString);
}

function isString($mayBeString) {
	return is_string($mayBeString);
}

function isPositiveInteger($mayBePositiveInteger) {
	return isInteger($mayBePositiveInteger) && $mayBePositiveInteger > 0;
}

function representPositiveInteger($mayBePositiveInteger) {
	return isPositiveInteger(stringToIntIfNumeric($mayBePositiveInteger));
}

function isNumeric($mayBeNumeric) {
	return is_numeric($mayBeNumeric);	
}

function isArray($mayBeArray) {
	return is_array($mayBeArray);
}

function isEmptyArray($mayBeNonEmptyArray) {
	return (isArray($mayBeNonEmptyArray) && empty($mayBeNonEmptyArray));
}

function isNonEmptyArray($mayBeNonEmptyArray) {
	return (is_array($mayBeNonEmptyArray) && !empty($mayBeNonEmptyArray));
}

function arrayHasIndex($array, $index) {
	return array_key_exists($index, $array);
}

function inArray($needle, $array) {
	return in_array($needle, $array);
}

function isKeyInArray($key, $array) {
	return issetAndNotEmpty($array) && arrayHasIndex($array, $key);
}

function isNotEmptyStringAndIsKeyInArray($str, $array) {
	return issetAndNotEmpty($str) && arrayHasIndex($array, $str);
}

function arrayContainStringThatContains($arrayOfStrings, $containingWord) {
	foreach ($arrayOfStrings as $str) {
		if (stringContains($containingWord, $str)) {
			return true;
		}
	}
	return false;
}

function stringContains($str, $wordToSearchFor) {
	return strpos($str, $wordToSearchFor) !== FALSE;
}

function stringContainsCaseInsensitive($str, $wordToSearchFor) {
	return stripos($str, $wordToSearchFor) !== FALSE;
}

function regexpMatch($str, $pattern) {
	if (substr($pattern, 0, 1) == '/') {
		$pattern = '/(*UTF8)' . substr($pattern, 1);
	} else {
		$pattern = '/(*UTF8)' . $pattern . '/';
	}
	
	return preg_match($pattern, $str);
}



/*
 *  Primitive variable-altering-functions
 */
function stringReplaceCaseInsensitive($str, $wordToSearchFor, $replaceWith) {
	return str_ireplace($wordToSearchFor, $replaceWith, $str);
}

function stringReplaceCaseSensitive($strOrStringArray, $wordToSearchFor, $replaceWith) {
	return str_replace($wordToSearchFor, $replaceWith, $strOrStringArray);
}

function removeChar($strOrStringArray, $char) {
	return stringReplaceCaseSensitive($strOrStringArray, $char, '');
}

function removeDigitsFromString($str) {
	return preg_replace('/[0-9]+/', '', $str);
}

function removeNonDigitsFromString($str) {
	return preg_replace('/[^0-9]+/', '', $str);
}

function stringToIntIfNumeric($mayBeNumeric) { // is actually stringToNumeric 
	if (is_numeric($mayBeNumeric)) {
		return $mayBeNumeric + 0;
	} else {
		return $mayBeNumeric;
	}
}

function lowerCase($str) {
	return mb_strtolower($str);
}


function uppererCase($str) {
	return mb_strtoupper($str);
}


function removeElementFromArray($array, $key) {
	if (arrayHasIndex($array, $key)) {
		unset($array[$key]);
	}
	return $array;
}

function mergeArrays($arrayA, $arrayB) {
	return array_merge($arrayA,$arrayB);
}

function arraySum($mayBeArray) {
	if (isNonEmptyArray($mayBeArray)) {
		return array_sum($mayBeArray);
	}
	return 0;
}

function arraySumOfKey($mayBeArray, $key) {
	if (!isNonEmptyArray($mayBeArray)) {
		return 0;
	}
	$sum = 0;
	foreach ($mayBeArray as $element) {
		if (arrayHasIndex($element, $key)) {
			$sum += $element[$key];
		}
	}
	return $sum;
}

function arrayWithoutFirst($array) {
	return array_slice($array, 1);
}

function getUnixNow() {
	return time();
}

function getTodayUnixDate() {
	return mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
}

function stringToUnixDate($stringDate) {
	if (!isString($stringDate) || !stringContains($stringDate, '.')) {
		return false;
	}
	return strtotime($stringDate);
}


function stringOrTimestampToTimestamp($stringOrTimestamp) {
	if (isString($stringOrTimestamp) || stringContains($stringOrTimestamp, '.')){
		return strtotime($stringOrTimestamp);
	}
	return $stringOrTimestamp;
}

function getDateFromTimestamp($date, $format) {
	return date($format, $date);
}

/*
 *  Error message functions
 */
function setErrorMessage($errorMessage) {
	$_SESSION['errorMessages'][] = $errorMessage;
	return true;
}

/*
 *  Input validating functions
 */
function ifNullSetErrorMessageReturnTrue($mayBeNull, $errorMessage) {
	if (isNull($mayBeNull)) {
		setErrorMessage($errorMessage);
		return true;
	}
	return false;
}

function ifNotNumericSetErrorMessageReturnTrue($mayBeNotNumeric, $errorMessage) {
	if (!isNumeric($mayBeNotNumeric)) {
		setErrorMessage($errorMessage);
		return true;
	}
	return false;
}

function ifNotPositiveIntegerSetErrorMessageReturnTrue($mayBePositiveInteger, $errorMessage) {
	if (!isPositiveInteger($mayBePositiveInteger)) {
		setErrorMessage($errorMessage);
		return true;
	}
	return false;
}

function ifPositiveIntegerSetErrorMessageReturnTrue($mayBePositiveInteger, $errorMessage) {
	if (isPositiveInteger($mayBePositiveInteger)) {
		setErrorMessage($errorMessage);
		return true;
	}
	return false;
}
function ifNotRepresentPositiveIntegerSetErrorMessageReturnTrue($mayBePositiveInteger, $errorMessage) {
	if (!representPositiveInteger($mayBePositiveInteger)) {
		setErrorMessage($errorMessage);
		return true;
	}
	return false;
}

function ifNotArrayWithElementsSetErrorMessageReturnTrue($mayBeNonEmptyArray, $errorMessage) {
	if (!isNonEmptyArray($mayBeNonEmptyArray)) {
		setErrorMessage($errorMessage);
		return true;
	}
	return false;
}


/*
 *  Other functions
 */
function percentOf($a, $b, $decimalsInAnswer = 2) {
	if (!isNumeric($a) || !isNumeric($b) || $b <= 0) {
		return 0;
	}
	return round(($a /$b)*100, $decimalsInAnswer);
}


function getNorToAsciiArray() {
	return array('nor' => array('Æ', 'Ø', 'Å', 'æ', 'ø', 'å'), 'ascii' => array('-A,E_', '-O,E_', '-A,A_', '-e,a_', '-o,e_', '-a,a_'));
}

function norToAscii($str) {
	$norToAsciiArray = getNorToAsciiArray();
	return stringReplaceCaseSensitive($str, $norToAsciiArray['nor'], $norToAsciiArray['ascii']);
}

function asciiToNor($str) {
	$norToAsciiArray = getNorToAsciiArray();
	return stringReplaceCaseSensitive($str, $norToAsciiArray['ascii'], $norToAsciiArray['nor']);
}



function listFilesInDir($path) {
	$path = norToAscii($path);
	$files = array();
	
	if (!file_exists ($path)) {
		return $files; 
	}
	
	$filesGlob = array_filter(glob($path . '*'), 'is_file');
	foreach ($filesGlob as $filename) {
		$files[] = asciiToNor(getSuffixAfterSlash($filename));
	}
	
	return $files;
}

function listDirectoriesInDir($path) {
	$path = norToAscii($path);
	$directories = glob($path . '*' , GLOB_ONLYDIR);
	for ($i = 0; $i < count($directories); $i++) {
		$directories[$i] = asciiToNor(getSuffixAfterSlash($directories[$i]));
	} 
	return $directories;
}

function recursiveRegExpFileSearch($folder, $pattern) {
    $dir = new RecursiveDirectoryIterator($folder);
    $ite = new RecursiveIteratorIterator($dir);
    $files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
    $fileList = array();
    foreach($files as $filenameArray) { 
    	$path = $filenameArray[0];
    	if (pathIsFile($path) && regexpMatch(getSuffixAfterSlash($path), $pattern)) {
    		$fileList[] = asciiToNor($path);
    	}
    }
    return $fileList;
}



function getSuffixAfter($str, $needle) {
	return substr($str, strrpos($str, $needle) + 1);
}


?>