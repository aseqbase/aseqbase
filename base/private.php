<?php
//echo("<br>__DIR__: ".__DIR__); echo("<br>BASE_DIR: ".$GLOBALS["BASE_DIR"]); echo("<br>DIR: ".$GLOBALS["DIR"]); die;
if(
	$_SERVER["REQUEST_METHOD"] != "POST"
	//|| $_SERVER['SERVER_ADDR'] !== $_SERVER['REMOTE_ADDR']
) die();

require_once(__DIR__."/initialize.php");
if(ACCESS()){
	$path = $_REQUEST[\_::$CONFIG->PathKey];
	if(isset($_REQUEST[\_::$CONFIG->PathKey])) unset($_REQUEST[\_::$CONFIG->PathKey]);
	if(isset($_GET[\_::$CONFIG->PathKey])) unset($_GET[\_::$CONFIG->PathKey]);
	if(isset($_POST[\_::$CONFIG->PathKey])) unset($_POST[\_::$CONFIG->PathKey]);
	RUN("private/".NormalizePath($path));
}
?>