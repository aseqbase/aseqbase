<?php
$path = implode("/", array_slice(explode("/", \_::$Address->Direction), 1));
$parent = compute( "tag/get", ["Name" =>$path]);
$computeData = grab($data, "Compute") ?? [];
$filter = grab($computeData, "Filter") ?? [];
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