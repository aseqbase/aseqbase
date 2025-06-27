<?php
route("content", [
    "Compute" => [
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