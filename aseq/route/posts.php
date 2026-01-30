<?php
route("contents", [
    "View"=>[
        "DefaultTitle" => "Posts",
        "MaximumColumns"=> 2,
        "Root" => "/post/",
        "CollectionRoot" => "/posts/"
    ],
    "ErrorHandler" => "Could not find related post"
]);
?>