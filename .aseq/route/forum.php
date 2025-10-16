<?php
route("content", [
    "Compute" => [
        "Filter" => [
            "Type" => "Forum"
        ]
    ],
    "View"=>[
        "Root" => "/forum/",
        "CollectionRoot" => "/forums/",
    ],
    "ErrorHandler" => "Could not find related forum"
]);
?>