<?php
if(
	$_SERVER["REQUEST_METHOD"] != "POST"
	//|| $_SERVER['SERVER_ADDR'] !== $_SERVER['REMOTE_ADDR']
) die();

require_once(__DIR__."/initialize.php");
if(ACCESS(\_::$CONFIG->UserAccess)){
	$path = $_REQUEST[\_::$CONFIG->PathKey];
	if(isset($_REQUEST[\_::$CONFIG->PathKey])) unset($_REQUEST[\_::$CONFIG->PathKey]);
	if(isset($_GET[\_::$CONFIG->PathKey])) unset($_GET[\_::$CONFIG->PathKey]);
	if(isset($_POST[\_::$CONFIG->PathKey])) unset($_POST[\_::$CONFIG->PathKey]);
	RUN("private/".NormalizePath($path));
}
?>