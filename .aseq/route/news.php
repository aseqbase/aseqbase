<?php
route("content", [
    "Logic" => [
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