<?php
$query = \Req::Receive("q") ?? \Req::Receive("Query");
$args = iterator_to_array(logic("content/all", [
    "Filter" => [
        "Query" => $query,
        "Cat" => \Req::Receive("Cat")
    ],
    "Order" => null
]));

view("contents", [
    "Title" => "Search Results",
    "WindowTitle" => [\Req::Receive("Query") ?? \Req::Receive("q", \Req::Receive("Cat")), \Req::Receive("Type" )],
    "Description" => "Found <b>\"" . count($args) . "\"</b> results for searching <b>\"$query\"</b>!",
    "ShowRoute" => true,
    "Items" => $args
]);
?>