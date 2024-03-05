<?php
LIBRARY("Query");
use MiMFa\Library\Query;
$path = implode("/", array_slice(explode("/",\_::$DIRECTION),1));
$parent = Query::FindCategory($path,[]);
if(count($parent)<1){
    VIEW("404");
    return;
}
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
$templ->WindowTitle = [getValid($parent,"Title")??getValid($parent,"Name")];
$templ->Content = function() use($parent,$path){
    MODULE("Page");
    $module = new \MiMFa\Module\Page();
    $module->Item = $parent;
    $module->Draw();
    MODULE("PostCollection");
    $module = new \MiMFa\Module\PostCollection();
    $module->ShowRoute = false;
    $module->DefaultImage = \_::$INFO->FullLogoPath;
    $module->Items = Query::SearchContents(RECEIVE("q"),$path,RECEIVE("t")??RECEIVE("type"));
    $module->Draw();
};
$templ->Draw();
?>