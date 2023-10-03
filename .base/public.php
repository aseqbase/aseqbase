<?php
require_once(__DIR__."/initialize.php");
if(ACCESS()){
	if(isValid($path = RECEIVE(\_::$CONFIG->PathKey))){
        if(isset($_REQUEST[\_::$CONFIG->PathKey])) unset($_REQUEST[\_::$CONFIG->PathKey]);
        if(isset($_GET[\_::$CONFIG->PathKey])) unset($_GET[\_::$CONFIG->PathKey]);
        if(isset($_POST[\_::$CONFIG->PathKey])) unset($_POST[\_::$CONFIG->PathKey]);
        RUN("public/".NormalizePath($path));
    } else RUN(normalizePath(\_::$DIRECTION));
}
?>