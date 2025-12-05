<?php
route("content", [
    "View"=>[
        "Root" => "/post/",
        "CollectionRoot" => \_::$Router->CategoryRoot
    ],
    "ErrorHandler" => "Could not find related post"
]);
?>