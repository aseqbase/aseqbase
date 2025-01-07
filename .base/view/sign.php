<?php
$path = \_::$DIRECTION;
if(!isEmpty($_POST)) PART("sign/".strtolower(implode("/", array_slice(explode("/",$path),1))));
elseif(isValid($path)) {
    TEMPLATE("Main");
    $templ = new \MiMFa\Template\Main();
    $templ->Content = function() use($path){
        PART("sign/".strtolower(implode("/", array_slice(explode("/",$path),1))));
    };
    $templ->Draw();
} else VIEW("404");
?>