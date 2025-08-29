<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__."/initialize.php");
if(
    startsWith(DIRECTORY_SEPARATOR.\_::$Direction, MiMFa\Library\User::$HandlerPath) ||
	inspect(\_::$Config->VisitAccess, assign:true)) {
	if(isValid(\_::$Request)) \_::$Back->Router->Handle();
    else route(\_::$Config->DefaultRouteName);
}
?>