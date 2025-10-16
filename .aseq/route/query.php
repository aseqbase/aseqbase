<?php
$viewData = grab($data, "View") ?? [];
$computeData = grab($data, "Compute") ?? [];
$filter = grab($computeData, "Filter") ?? [];
return route("search", [
    "Compute" => [
        "Filter" => [
            "Category" => implode("/", array_slice(explode("/", \_::$Base->Direction), 1)),
            ...$filter
        ],
        ...$computeData
    ],
    "View" => [
        "Title" => "Query Results",
        ...$viewData
    ],
    ...$data
]);
?>