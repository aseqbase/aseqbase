<?php
route("contents", [
    "Compute" => [
        "Filter" => [
            "Type" => "News"
        ]
    ],
    "View"=>[
        "DefaultTitle" => "Latest News",
        "Image"=>"comments",
        "MaximumColumns"=> 3,
        "Root" => "/news/",
        "CollectionRoot" => "/newses/"
    ],
    "ErrorHandler" => "Could not find related news"
]);
?>