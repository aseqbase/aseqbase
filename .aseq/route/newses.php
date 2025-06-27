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
        "RootRoute" => "/news/",
        "CollectionRoute" => "/newses/"
    ],
    "ErrorHandler" => "Could not find related news"
]);
?>