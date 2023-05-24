<?php
$path = implode("/", array_slice(explode("/",\_::$DIRECTION),1));
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
if(!isValid($path))
    $templ->Content = function(){
        PAGE("home");
    };
else
    $templ->Content = function() use($path){
        PAGE(normalizePath($path));
    };
$templ->Draw();
?>