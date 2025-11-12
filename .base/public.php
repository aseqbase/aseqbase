
<?php
require_once(__DIR__.DIRECTORY_SEPARATOR."initialize.php");
if(auth()) run(normalizePath(\_::$Address->Direction));
?>