<?php
$viewData = grab($data, "View") ?? [];
$logicData = grab($data, "Compute") ?? [];
$filter = grab($logicData, "Filter") ?? [];
return route("search", [
    "Compute" => [
        "Filter" => [
            "Category" => implode("/", array_slice(explode("/", \Req::$Direction), 1)),
            ...$filter
        ],
        ...$logicData
    ],
    "View" => [
        "Title" => "Query Results",
        ...$viewData
    ],
    ...$data
]);
?>