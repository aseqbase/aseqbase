<?php
route("content", [
    "Logic" => [
        "Filter" => [
            "Type" => "Forum"
        ]
    ],
    "View"=>[
        "RootRoute" => "/forum/",
        "CollectionRoute" => "/forums/",
    ],
    "ErrorHandler" => "Could not find related forum"
]);
?>