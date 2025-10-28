<?php
use MiMFa\Library\Convert;

$data = $data??[];
function findContent($router, &$data)
{
    $computeData = get($data, "Compute")??[];
    if(!is_array($computeData)) return Convert::By($computeData);
    $path = explode("/", trim(urldecode($router->Direction) ?? "", "/\\"));
    $name = last($path);
    if (count($path) > 1)
        $path = implode("/", array_slice($path, 0, count($path) - 1));
    else
        $path = null;
    $filter = pop($computeData, "Filter") ?? [];
    $received = receive();
    return compute(pop($computeData, "ComputeName")??"content/get", [
        "Name"=>$name,
        "Filter" => [
            "Query"=> pop($filter, "Query")??getBetween($received, "Query")??$name,
            "Category" => pop($filter, "Category")??getBetween($received, "Category")??$path,
            "Type"=> pop($filter, "Type")??get($received, "Type"),
            "Tag"=> pop($filter, "Tag")??get($received, "Tag"),
            ...$filter??[]
        ],
        ...$computeData??[]
    ]);
}

(new Router())
    ->Get(function ($router) use ($data) {
        $doc = findContent($router, $data);
        $viewData = get($data, "View")??[];
        if(!is_array($viewData)) return Convert::By($viewData, $doc);
        if (isEmpty($doc)){
            $c = get($viewData, "ErrorHandler");
            if($c) return Convert::By($c, $router);
            else route("contents", $data);
        } else view(
                pop($viewData, "ViewName") ?? "content",
                isEmpty($viewData) ? $doc : [...$doc, ...$viewData]
            );
    })
    ->Default(function ($router) use ($data) {
        $doc = findContent($router, $data);
        if (isEmpty($doc))
            response(__(get($data, "ErrorHandler") ?? "Could not find related content"));
        elseif (compute("contact/comment", $doc))
            reload();
    })
    ->Handle();