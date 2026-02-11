<?php
$viewData = pop($data, "View") ?? [];
$computeData = pop($data, "Compute") ?? [];
$filter = pop($computeData, "Filter") ?? [];
return route("search", [
    "Compute" => [
        "Filter" => [
            "Category" => implode("/", array_slice(explode("/", \_::$Address->UrlRoute), 1)),
            ...$filter
        ],
        ...$computeData
    ],
    "View" => [
        "Title" => "Query Results",
        ...$viewData
    ],
    ...($data??[])
]);
?>