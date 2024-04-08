<?php
LIBRARY("Query");
use MiMFa\Library\Query;
$path = implode("/", array_slice(explode("/",\_::$DIRECTION),1));
$parent = Query::FindTag($path,[]);
if(count($parent)<1){
    VIEW("404");
    return;
}
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
$templ->WindowTitle = [getValid($parent, "Title")??getValid($parent, "Name")];
$templ->Content = function() use($parent, $path){
    MODULE("Navigation");
    $orders = explode(",", RECEIVE("order", default:"ASC"));
    $nav = new \MiMFa\Module\Navigation(Query::SearchContents(
        query:RECEIVE("q"),
        type:RECEIVE("type"),
        tag:$path,
        order:RECEIVE("sort")?\MiMFa\Library\Convert::ToSequence(loop(explode(",",RECEIVE("sort")), function($k, $v, $i) use($orders) { return [$v=>getValid($orders, $i%count($orders), "ASC")]; })):[]
    ));
    MODULE("PostCollection");
    $module = new \MiMFa\Module\PostCollection();
    $name = getValid($parent, "Name");
    $title = getValid($parent, "Title");
    $module->Title = !isEmpty($title) && strtolower(preg_replace("/\W*/","", $name))!=strtolower(preg_replace("/\W*/","", $title))? "$title ($name)" : between($title,$name);
    $module->Description = getValid($parent, "Description");
    $module->DefaultImage = getValid($parent, "Image", \_::$INFO->FullLogoPath);
    $module->ShowRoute = false;
    $module->Items = $nav->GetItems();
    $module->DoDraw();
    $nav->DoDraw();
};
$templ->Draw();
?>