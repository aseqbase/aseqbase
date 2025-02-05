<?php

use MiMFa\Library\Router;
use MiMFa\Module\CommentForm;
LIBRARY("Query");
use \MiMFa\Library\Query;
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

(new Router())
    ->GET(function() use($doc){
        TEMPLATE("Main");
        $templ = new \MiMFa\Template\Main();
        $templ->WindowTitle = [$doc['Title']];
        $templ->Content = function() use($doc){
            MODULE("Post");
            $module = new \MiMFa\Module\Post();
            $module->Item = $doc;
            $module->Draw();
        };
        $templ->Draw();
    })
    ->ALL(function() use($doc) {
        MODULE("CommentForm");
        $cc = new CommentForm();
        $cc->Relation = $doc['ID'];
        return $cc->Handle();
    })
->Handle();
?>