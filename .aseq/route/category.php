<?php
$path = implode("/", array_slice(explode("/",\Req::$Direction),1));
$parent = logic( "category/get", ["Name" =>$path]);
if(isEmpty($parent)) view(\_::$Config->DefaultViewName, ["Name" =>404]);
else view("contents", [
    ...$parent,
    "Items"=>logic("content/all", ["Filter"=>["Cat"=>$path]])
]);
?>