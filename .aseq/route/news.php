<?php
route("content", [
    "Compute" => [
        "Filter" => [
            "Type" => "News"
        ]
    ],
    "View"=>[
        "RootRoute" => "/news/",
        "CollectionRoute" => "/newses/",
    ],
    "ErrorHandler" => "Could not find related news"
]);
?>