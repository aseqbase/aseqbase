<?php
$viewData = grab($data, "View") ?? [];
$logicData = grab($data, "Logic") ?? [];
$filter = grab($logicData, "Filter") ?? [];
return route("search", [
    "Logic" => [
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