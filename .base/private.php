<?php
if(
	$_SERVER["REQUEST_METHOD"] != "POST"
	|| $_SERVER['SERVER_ADDR'] !== $_SERVER['REMOTE_ADDR']
) die(401);

require_once(__DIR__.DIRECTORY_SEPARATOR."initialize.php");
if(ACCESS(\_::$CONFIG->AdminAccess)){
	if(isValid($path = RECEIVE(\_::$CONFIG->PathKey))){
        if(isset($_REQUEST[\_::$CONFIG->PathKey])) unset($_REQUEST[\_::$CONFIG->PathKey]);
        if(isset($_GET[\_::$CONFIG->PathKey])) unset($_GET[\_::$CONFIG->PathKey]);
        if(isset($_POST[\_::$CONFIG->PathKey])) unset($_POST[\_::$CONFIG->PathKey]);
        RUN("private/".NormalizePath($path));
    } else RUN(normalizePath(\_::$DIRECTION));
}
?>