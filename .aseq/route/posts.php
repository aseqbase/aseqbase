<?php
route("contents", [
    "View"=>[
        "DefaultTitle" => "Posts",
        "MaximumColumns"=> 2,
        "RootRoute" => "/post/",
        "CollectionRoute" => "/posts/"
    ],
    "ErrorHandler" => "Could not find related post"
]);
?>