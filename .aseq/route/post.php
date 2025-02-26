<?php
use \MiMFa\Library\Router;
function findContent($direction){
    $path = explode("/", $direction);
    $name = last($path);
    if (count($path) > 1)
        $path = implode("/", array_slice($path, 0, count($path) - 1));
    else
        $path = null;
    return logic("content/get", [
        "Name" => $name,
        "Filter" => [
            "Cat" => $path
        ]
    ]);
}
(new Router())
    ->Route("posts")
        ->Get(function () {
            view("contents", [
                "Title" => "Posts",
                "RootPath" => \_::$Address->ContentPath,
                "Items" => logic("content/all")
        ]);
    })
    ->Route()->Get(function ($router) {
            $doc = findContent($router->Direction);
            if (isEmpty($doc)) route("404");
            else view("content" , $doc);
        })
    ->Default(function ($router) {
            $doc = findContent($router->Direction);
            if (isEmpty($doc)) \Res::Render(__("Could not find related document"));
            elseif(logic("comment/handle", $doc)) \Res::Load();
        })
    ->Handle();
?>