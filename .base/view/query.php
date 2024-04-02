<?php
LIBRARY("Query");
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
$templ->WindowTitle = [RECEIVE("q",default:\_::$DIRECTION),RECEIVE("type")];
$templ->Content = function(){
    MODULE("Navigation");
    $query = RECEIVE("q");
    $orders = explode(",", RECEIVE("order", default:"ASC"));
    $nav = new \MiMFa\Module\Navigation(iterator_to_array(\MiMFa\Library\Query::Search(
        query:$query,
        direction:implode("/", array_slice(explode("/",\_::$DIRECTION),1)),
        type:RECEIVE("type"),
        tag:RECEIVE("tag"),
        order:RECEIVE("sort")?\MiMFa\Library\Convert::ToSequence(loop(explode(",",RECEIVE("sort")), function($k, $v, $i) use($orders) { return [$v=>getValid($orders, $i%count($orders), "ASC")]; })):[]
    )));
    MODULE("PostCollection");
    $module = new \MiMFa\Module\PostCollection();
    $module->TitleTag = "h2";
    $module->Title = "Search Results";
    $module->Description = "Found <b>\"{$nav->Count}\"</b> results for searching <b>\"$query\"</b>!";
    $module->Class .= " page";
    $module->DefaultImage = \_::$INFO->FullLogoPath;
    $module->Items = $nav->GetItems();
    $module->DoDraw();
    $nav->DoDraw();
};
$templ->Draw();
?>