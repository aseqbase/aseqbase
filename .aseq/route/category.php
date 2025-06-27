<?php
$path = implode("/", array_slice(explode("/", \Req::$Direction), 1));
$parent = compute( "category/get", ["Name" =>$path]);
$logicData = grab($data, "Compute") ?? [];
$filter = grab($logicData, "Filter") ?? [];
if(isEmpty($parent)) view(\_::$Config->DefaultViewName, ["Name" =>404]);
return route("contents", [
    "Compute"=>[
        "Filter"=>[
            "Category"=>$path,
            ...$filter
        ],
        ...$logicData
    ],
    ...$parent,
    ...$data
]);
?>