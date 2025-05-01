<?php
$path = implode("/", array_slice(explode("/", \Req::$Direction), 1));
$parent = logic( "category/get", ["Name" =>$path]);
$logicData = grab($data, "Logic") ?? [];
$filter = grab($logicData, "Filter") ?? [];
if(isEmpty($parent)) view(\_::$Config->DefaultViewName, ["Name" =>404]);
return route("contents", [
    "Logic"=>[
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