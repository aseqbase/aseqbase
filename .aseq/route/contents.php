<?php
use MiMFa\Library\Convert;
$received = receive();
$computeData = get($data, "Compute") ?? [];
$filter = grab($computeData, "Filter") ?? [];
$cat = grab($filter, "Category") ?? get($received, "Category");
$type = grab($filter, "Type") ?? get($received, "Type");
$tag = grab($filter, "Tag") ?? get($received, "Tag");
$items = !is_array($computeData)?Convert::By($computeData):compute(grab($computeData, "ComputeName") ?? "content/all", [
    "Filter" => [
        "Query" => grab($filter, "Query") ?? get($received, "Query"),
        "Category" => $cat??\_::$Address->Direction,
        "Type" => $type,
        "Tag" => $tag,
        ...$filter??[]
    ],
    "Order" => grab($filter, "Order") ?? get($received, "Order"),
    "Limit" => grab($filter, "Limit") ?? get($received, "Limit")??-1,
    ...$computeData??[]
]);

$viewData = get($data, "View") ?? [];

if(!is_array($viewData))  return Convert::By($viewData, $items);

if (isEmpty($items)) {
    $c = get($viewData, "ErrorHandler");
    if ($c)
        return Convert::By($c, \_::$Router);
}

view(grab($viewData, "ViewName") ?? "contents", [
    "Title" => grab($viewData, "Title") ??  between($type, $tag, preg_replace("/\..*$/", "", \_::$Address->Page), $cat, grab($data, "DefaultTitle") ?? "Contents"),
    "Root" => grab($viewData, "Root") ?? \_::$Address->ContentRoot,
    "Description" => grab($viewData, "Description"),
    "Items" => $items,
    ...$viewData??[]
]);
?>