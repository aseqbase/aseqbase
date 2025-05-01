<?php
route("content", [
    "View"=>[
        "RootRoute" => "/post/",
        "CollectionRoute" => "/posts/"
    ],
    "ErrorHandler" => "Could not find related post"
]);
?>