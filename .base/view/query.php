<?php
LIBRARY("Query");
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
$templ->WindowTitle = __("Results")." - ".\_::$INFO->Name;
$templ->Content = function(){
    MODULE("Post");
    $module = new \MiMFa\Module\Post();
    $module->Class .= " page";
    $module->ShowRoute = false;
    $module->DefaultImage = \_::$INFO->FullLogoPath;
    $module->Item = \MiMFa\Library\Query::Query(
        isValid($_REQUEST,"q")? $_REQUEST["q"]:null,
        \_::$DIRECTION,
        isValid($_REQUEST,"type")? $_REQUEST["type"]:null
    );
    $module->Draw();
};
$templ->Draw();
?>