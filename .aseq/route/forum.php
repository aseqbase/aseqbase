<?php
use \MiMFa\Library\Router;
function findContent($direction){
    $path = explode("/", urldecode($direction));
    $name = last($path);
    if (count($path) > 1)
        $path = implode("/", array_slice($path, 0, count($path) - 1));
    else
        $path = null;

    return logic("content/get", [
        "Name" => $name,
        "Filter" => [
            "Cat" => $path,
            "Type" => "Forum"
        ]
    ]);
}
(new Router())
    ->Route("forums")->Get(function () {
        view("contents", [
            "Title" => "Forums",
            "RootRoute" => "/forum/",
            "Description" => "Join to a Forum",
            "Items" => logic("content/all", ["Filter" => ["Type" => "Forum"]])
        ]);
    })
    ->Route->Get(function ($router) {
        $doc = findContent($router->Direction);
        if (isEmpty($doc))
            view("contents", [
                "Title" => preg_replace("/\..*$/", "", \Req::$Page),
                "RootRoute" => "/forum/",
                "Description" => "Join to a Forum",
                "Items" => logic("content/all", [
                    "Filter" => [
                        "Type" => "Forum",
                        "Cat" => $router->Direction
                    ]
                ])
            ]);
        else
            view("content", $doc);
    })
    ->Default(function ($router) {
        $doc = findContent($router->Direction);
        if (isEmpty($doc))
            \Res::Render(__("Could not find related document"));
        elseif (logic("comment/handle", $doc))
            \Res::Reload();
    })
    ->Handle();
?>