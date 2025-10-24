<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__ . DIRECTORY_SEPARATOR . "global.php");
runSequence("route");
runSequence("initialize");
if (
    //startsWith(DIRECTORY_SEPARATOR . \_::$Router->Direction, \_::$User->HandlerPath) ||
    inspect(\_::$User->VisitAccess, assign: true)
) {
    runSequence("customize");
    run("revise");
    if (isValid(\_::$Router->Request))
        \_::$Router->Handle();
    else
        route(\_::$Router->DefaultRouteName);
}
runSequence("finalize");
?>