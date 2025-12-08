<?php
route("content", [
    "View"=>[
        "Root" => "/post/",
        "CollectionRoot" => \_::$Address->CategoryRoot
    ],
    "ErrorHandler" => "Could not find related post"
]);
?>