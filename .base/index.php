<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__."/initialize.php");
if(
    startsWith(DIRECTORY_SEPARATOR.\Req::$Direction, MiMFa\Library\User::$HandlerPath) ||
	inspect(\_::$Config->VisitAccess, assign:true, die:true)) {
	if(isValid(\Req::$Request)) \_::$Back->Router->Handle();
    else route(\_::$Config->DefaultRouteName);
}
?>