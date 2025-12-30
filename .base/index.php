<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__ . DIRECTORY_SEPARATOR . "global.php");

runSequence("route");//Is deprecated and will remove from version 8
initialize();
if (auth(\_::$User->VisitAccess, assign: true)) {
    customize();
    if (isValid(\_::$User->Request))
        \_::$Router->Handle();
    else
        route(\_::$Router->DefaultRouteName);
}
finalize();