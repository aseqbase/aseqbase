<?php
$query = \Req::Receive("q") ?? \Req::Receive("Query");
$args = logic("content/all", [
    "Filter" => [
        "Query" => $query,
        "Cat" => implode("/", array_slice(explode("/",\Req::$Direction),1))
    ],
    "Order" => null
]);

view("contents", [
    "Title" => "Query Results",
    "WindowTitle" => [\Req::Receive("Query") ?? \Req::Receive("q", default: \Req::$Direction), \Req::Receive("Type" )],
    "Description" => "Found <b>\"" . count($args) . "\"</b> results for searching <b>\"$query\"</b>!",
    "ShowRoute" => true,
    "Items" => $args
]);
?>