<?php
route("contents", [
    "Compute" => [
        "Filter" => [
            "Type" => "Forum"
        ]
    ],
    "View"=>[
        "DefaultTitle" => "Your Forums",
        "Description" => "Join to a Forum",
        "Image"=>"comments",
        "MaximumColumns"=> 1,
        "Root" => "/forum/",
        "CollectionRoot" => "/forums/"
    ],
    "ErrorHandler" => "Could not find related forum"
]);
?>