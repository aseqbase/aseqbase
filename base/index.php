<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__ . DIRECTORY_SEPARATOR . "global.php");

initialize();
if (auth(\_::$User->VisitAccess, assign: true)) {
    customize();
    if (isValid(\_::$Address->UrlRequest))
        \_::$Router->Handle();
    else
        route(\_::$Router->DefaultRouteName);
}
finalize();