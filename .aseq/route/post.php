<?php
route("content", [
    "View"=>[
        "Root" => "/post/",
        "CollectionRoot" => \_::$Base->CategoryRoot
    ],
    "ErrorHandler" => "Could not find related post"
]);
?>