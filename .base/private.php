<?php
if(
	$_SERVER["REQUEST_METHOD"] != "POST"
	|| $_SERVER['SERVER_ADDR'] !== $_SERVER['REMOTE_ADDR']
) exit(401);

require_once(__DIR__.DIRECTORY_SEPARATOR."initialize.php");
if(inspect(\_::$Config->AdminAccess)) run(normalizePath(\Req::$Direction));
?>