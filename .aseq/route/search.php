<?php
$computeData = pop($data, "Compute")??[];
$filter = pop($computeData, "Filter")??[];
$viewData = pop($data, "View")??[];
$received = receive();
$query = getBetween($received, "q", "Query")??urldecode(\_::$Router->Page);
$cat = getBetween($received, "Cat", "Category");
return route("contents", [
    "Compute"=>[
        "Filter"=>[
            "Query" => pop($filter, "Query") ?? $query,
            "Category" => pop($filter, "Category") ?? $cat,
        ]
    ],
    "View" => function($items) use($viewData,$query, $cat){
        if(count($items)===1) view("content", $items[0]);
        else view(pop($viewData, "ViewName") ?? "contents", [
            "Title" => pop($viewData, "Title") ?? "Search Results",
            "WindowTitle" => pop($viewData, "WindowTitle") ?? get($items, "Title") ?? [$query, $cat],
            "Description" => pop($viewData, "Description") ?? "Found <b>\"" . count($items) . "\"</b> results for searching <b>\"$query\"</b>!",
            "ShowRoot" => pop($viewData, "ShowRoot") ?? true,
            "Root" => pop($viewData, "Root") ?? \_::$Address->ContentRoot,
            "Items" => $items,
            ...$viewData
        ]);
    },
    ...$data??[]
]);
?>