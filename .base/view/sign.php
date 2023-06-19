<?php
$path = \_::$DIRECTION;
if(isValid($path)) PART("sign/".strtolower(implode("/", array_slice(explode("/",$path),1))));
else VIEW("404");
?>