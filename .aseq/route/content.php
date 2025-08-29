<?php
use MiMFa\Library\Convert;
use \MiMFa\Library\Router;
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
    $filter = grab($computeData, "Filter") ?? [];
    $received = receive();
    return compute(grab($computeData, "ComputeName")??"content/get", [
        "Name"=>$name,
        "Filter" => [
            "Query"=> grab($filter, "Query")??getBetween($received, "Query")??$name,
            "Category" => grab($filter, "Category")??getBetween($received, "Category")??$path,
            "Type"=> grab($filter, "Type")??get($received, "Type"),
            "Tag"=> grab($filter, "Tag")??get($received, "Tag"),
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
                grab($viewData, "ViewName") ?? "content",
                isEmpty($viewData) ? $doc : [...$doc, ...$viewData]
            );
    })
    ->Default(function ($router) use ($data) {
        $doc = findContent($router, $data);
        if (isEmpty($doc))
            render(__(get($data, "ErrorHandler") ?? "Could not find related content"));
        elseif (compute("comment/handle", $doc))
            reload();
    })
    ->Handle();