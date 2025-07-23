<?php
route("content", [
    "View"=>[
        "RootRoute" => "/post/",
        "CollectionRoute" => "/cat/"
    ],
    "ErrorHandler" => "Could not find related post"
]);
?>