<?php
LIBRARY("Query");
use \MiMFa\Library\Query;
use \MiMFa\Library\Router;
$path = array_slice(explode("/",\_::$DIRECTION),1);
$name = last($path);
if(count($path) > 1) $path = implode("/", array_slice($path,0, count($path) - 1));
else $path = null;

if(!isValid($name)){
    VIEW("category");
    return;
}

$doc = Query::FindContent(
    name:$name,
    direction:$path??RECEIVE("cat"),
    type:RECEIVE("type"),
    tag:RECEIVE("tag")
);

if(isEmpty($doc)){
    VIEW("category");
    return;
}
if($doc['Type'] != "Forum") return VIEW("forums");

(new Router())
    ->GET(function() use($doc){
        TEMPLATE("Main");
        $templ = new \MiMFa\Template\Main();
        $templ->WindowTitle = [$doc['Title']];
        $templ->Content = function() use($doc){
            MODULE("Forum");
            $module = new \MiMFa\Module\Forum();
            $module->Item = $doc;
            $module->Draw();
        };
        $templ->Draw();
    })
    ->ALL(function() use($doc){
        if(RECEIVE()) {
            MODULE("CommentForm");
            $cc = new \MiMFa\Module\CommentForm();
            $cc->Relation = $doc['ID'];
            return $cc->Handle();
        }
    })
    ->Handle();
?>