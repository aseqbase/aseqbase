<?php
route("content", [
    "Compute" => [
        "Filter" => [
            "Type" => "News"
        ]
    ],
    "View"=>[
        "Root" => "/news/",
        "CollectionRoot" => "/newses/",
    ],
    "ErrorHandler" => "Could not find related news"
]);
?>