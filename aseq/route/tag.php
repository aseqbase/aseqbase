<?php
$path = implode("/", array_slice(explode("/", \_::$Address->UrlRoute), 1));
$parent = compute( "tag/get", ["Name" =>$path]);
$computeData = pop($data, "Compute") ?? [];
$filter = pop($computeData, "Filter") ?? [];
if(isEmpty($parent)) view(\_::$Front->DefaultViewName, ["Name" =>404]);
return route("contents", [
    "Compute"=>[
        "Filter"=>[
            "Tag"=>$path,
            ...$filter??[]
        ],
        ...$computeData??[]
    ],
    ...$parent??[],
    ...$data??[]
]);
?>