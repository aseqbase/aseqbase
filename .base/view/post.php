<?php
use MiMFa\Library\DataBase;
$path = implode("/", array_slice(explode("/",\_::$DIRECTION),1));
if(!isValid($path)){
    TEMPLATE("Main");
    $templ = new \MiMFa\Template\Main();
    $templ->Content = function() {
        PART("post-collection");
    };
    $templ->Draw();
    return;
}
$items = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Content","*","(Name=:Name OR ID=:ID) AND `Access`<=".getAccess(),[":Name"=>$path,":ID"=>$path]);
if(count($items)<1){
    VIEW("404");
    return;
}
$doc = $items[0];
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
$templ->WindowTitle = $doc['Title']." - ".\_::$INFO->Name;
$templ->Content = function() use($doc){
    MODULE("Post");
    $module = new \MiMFa\Module\Post();
    $module->Item = $doc;
    $module->Draw();
};
$templ->Draw();

?>