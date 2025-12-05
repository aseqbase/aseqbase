<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__ . DIRECTORY_SEPARATOR . "global.php");
runSequence("route");
runSequence("initialize");
if (auth(\_::$User->VisitAccess, assign: true)) {
    run("customize");
    if (isValid(\_::$User->Request))
        \_::$Router->Handle();
    else
        route(\_::$Router->DefaultRouteName);
}
runSequence("finalize");
?>