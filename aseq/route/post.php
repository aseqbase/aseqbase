<?php
route("content", [
    "View"=>[
        "Root" => "/post/",
        "CollectionRoot" => \_::$Address->CategoryRootUrlPath
    ],
    "ErrorHandler" => "Could not find related post"
]);
?>