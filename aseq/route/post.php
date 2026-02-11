<?php
route("content", [
    "View"=>[
        "Root" => "/post/",
        "CollectionRoot" => \_::$Address->CategoryRootPath
    ],
    "ErrorHandler" => "Could not find related post"
]);
?>