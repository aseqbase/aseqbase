<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__."/initialize.php");
if(
    startsWith(DIRECTORY_SEPARATOR.\_::$Router->Direction, \_::$User->HandlerPath) ||
	inspect(\_::$User->VisitAccess, assign:true)) {
	if(isValid(\_::$Router->Request)) \_::$Router->Handle();
    else route(\_::$Router->DefaultRouteName);
}
?>