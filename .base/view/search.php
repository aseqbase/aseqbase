<?php
LIBRARY("Query");
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
$templ->WindowTitle = [isValid($_REQUEST,"q")? $_REQUEST["q"]:\_::$DIRECTION,isValid($_REQUEST,"type")? $_REQUEST["type"]:null];
$templ->Content = function(){
    MODULE("PostCollection");
    $module = new \MiMFa\Module\PostCollection();
    $module->Class .= " page";
    $module->ShowRoute = false;
    $module->DefaultImage = \_::$INFO->FullLogoPath;
    $module->Items = \MiMFa\Library\Query::Search(
        isValid($_REQUEST,"q")? $_REQUEST["q"]:null,
        \_::$DIRECTION,
        isValid($_REQUEST,"type")? $_REQUEST["type"]:null
    );
    $module->Draw();
};
$templ->Draw();
?>