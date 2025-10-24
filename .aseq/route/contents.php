<?php
use MiMFa\Library\Convert;
$received = receive();
$computeData = get($data, "Compute") ?? [];
$filter = pop($computeData, "Filter") ?? [];
$cat = pop($filter, "Category") ?? get($received, "Category");
$type = pop($filter, "Type") ?? get($received, "Type");
$tag = pop($filter, "Tag") ?? get($received, "Tag");
$items = !is_array($computeData)?Convert::By($computeData):compute(pop($computeData, "ComputeName") ?? "content/all", [
    "Filter" => [
        "Query" => pop($filter, "Query") ?? get($received, "Query"),
        "Category" => $cat??\_::$Address->Direction,
        "Type" => $type,
        "Tag" => $tag,
        ...$filter??[]
    ],
    "Order" => pop($filter, "Order") ?? get($received, "Order"),
    "Limit" => pop($filter, "Limit") ?? get($received, "Limit")??-1,
    ...$computeData??[]
]);

$viewData = get($data, "View") ?? [];

if(!is_array($viewData))  return Convert::By($viewData, $items);

if (isEmpty($items)) {
    $c = get($viewData, "ErrorHandler");
    if ($c)
        return Convert::By($c, \_::$Router);
}

view(pop($viewData, "ViewName") ?? "contents", [
    "Title" => pop($viewData, "Title") ??  between($type, $tag, preg_replace("/\..*$/", "", \_::$Address->Page), $cat, pop($data, "DefaultTitle") ?? "Contents"),
    "Root" => pop($viewData, "Root") ?? \_::$Address->ContentRoot,
    "Description" => pop($viewData, "Description"),
    "Items" => $items,
    ...$viewData??[]
]);
?>