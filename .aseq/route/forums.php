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
        "RootRoute" => "/forum/",
        "CollectionRoute" => "/forums/"
    ],
    "ErrorHandler" => "Could not find related forum"
]);
?>