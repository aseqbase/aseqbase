<?php
$computeData = grab($data, "Compute")??[];
$filter = grab($computeData, "Filter")??[];
$viewData = grab($data, "View")??[];
$received = receive();
$query = getBetween($received, "q", "Query");
$cat = getBetween($received, "Cat", "Category");
return route("contents", [
    "Compute"=>[
        "Filter"=>[
            "Query" => grab($filter, "Query") ?? $query,
            "Category" => grab($filter, "Category") ?? $cat,
        ]
    ],
    "View" => function($items) use($viewData,$query, $cat){
        if(count($items)===1) view("content", $items[0]);
        else view(grab($viewData, "ViewName") ?? "contents", [
            "Title" => grab($viewData, "Title") ?? "Search Results",
            "WindowTitle" => grab($viewData, "WindowTitle") ?? get($items, "Title") ?? [$query, $cat],
            "Description" => grab($viewData, "Description") ?? "Found <b>\"" . count($items) . "\"</b> results for searching <b>\"$query\"</b>!",
            "ShowRoot" => grab($viewData, "ShowRoot") ?? true,
            "Root" => grab($viewData, "Root") ?? \_::$Base->ContentRoot,
            "Items" => $items,
            ...$viewData
        ]);
    },
    ...$data??[]
]);
?>