<?php
use MiMFa\Library\Convert;
$received = \Req::Receive();
$logicData = get($data, "Compute") ?? [];
$filter = grab($logicData, "Filter") ?? [];
$cat = grab($filter, "Category") ?? get($received, "Category");
$type = grab($filter, "Type") ?? get($received, "Type");
$tag = grab($filter, "Tag") ?? get($received, "Tag");
$items = !is_array($logicData)?Convert::By($logicData):compute(grab($logicData, "ComputeName") ?? "content/all", [
    "Filter" => [
        "Query" => grab($filter, "Query") ?? get($received, "Query"),
        "Category" => $cat??\_::$Back->Router->Direction,
        "Type" => $type,
        "Tag" => $tag,
        ...$filter
    ],
    "Order" => grab($filter, "Order") ?? get($received, "Order"),
    "Limit" => grab($filter, "Limit") ?? get($received, "Limit")??-1,
    ...$logicData
]);

$viewData = get($data, "View") ?? [];

if(!is_array($viewData))  return Convert::By($viewData, $items);

if (isEmpty($items)) {
    $c = get($viewData, "ErrorHandler");
    if ($c)
        return Convert::By($c, \_::$Back->Router);
}

view(grab($viewData, "ViewName") ?? "contents", [
    "Title" => grab($viewData, "Title") ??  between($type, $tag, preg_replace("/\..*$/", "", \Req::$Page), $cat, grab($data, "DefaultTitle") ?? "Contents"),
    "RootRoute" => grab($viewData, "RootRoute") ?? \_::$Address->ContentRoute,
    "Description" => grab($viewData, "Description"),
    "Items" => $items,
    ...$viewData
]);
?>