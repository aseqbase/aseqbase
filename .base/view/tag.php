<?php
use MiMFa\Library\DataBase;
$path = implode("/", array_slice(explode("/",$_GET[\_::$CONFIG->PathKey]),1));
if(!isValid($path)){
    TEMPLATE("Main");
    $templ = new \MiMFa\Template\Main();
    $templ->Content = function() {
        PART("post-collection");
    };
    $templ->Draw();
    return;
}
$items = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Tag","*","(Name=:Name OR ID=:ID) AND `Access`<=".getAccess(),[":Name"=>$path,":ID"=>$path]);
if(count($items)<1){
    VIEW("404");
    return;
}
$doc = $items[0];
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
$templ->WindowTitle = (getValid($doc,"Title")??getValid($doc,"Name"))." - ".\_::$INFO->Name;
$templ->Content = function() use($doc){
    MODULE("Post");
    $module = new \MiMFa\Module\Post();
    $module->Item = $doc;
    $module->Draw();
    MODULE("PostCollection");
    $module = new \MiMFa\Module\PostCollection();
    $module->ShowRoute = false;
    $module->DefaultImage = \_::$INFO->FullLogoPath;
    $module->Items = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Content","*",
        "TagIDs LIKE \"%|".$doc["ID"]."|%\" AND `Access`<=".getAccess());
    $module->Draw();
};
$templ->Draw();
?>