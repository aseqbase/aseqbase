<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__."/initialize.php");
if(
    startsWith(DIRECTORY_SEPARATOR.\_::$Aseq->Direction, \User::$HandlerPath) ||
	inspect(\_::$Config->VisitAccess, assign:true)) {
	if(isValid(\_::$Aseq->Request)) \_::$Aseq->Handle();
    else route(\_::$Config->DefaultRouteName);
}
?>