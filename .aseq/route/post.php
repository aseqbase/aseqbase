<?php
route("content", [
    "View"=>[
        "RootRoute" => "/post/",
        "CollectionRoute" => \_::$Address->CategoryRoute
    ],
    "ErrorHandler" => "Could not find related post"
]);
?>