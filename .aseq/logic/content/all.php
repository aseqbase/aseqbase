<?php
$Filter = grab($data, "Filter");
return \_::$Back->Query->SearchContents(
    query: grab($Filter, "Query") ?? \Req::Receive("q") ?? \Req::Receive("Query"),
    direction: grab($Filter, "Cat") ?? \Req::Receive("Cat"),
    type: grab($Filter, "Type" ) ?? \Req::Receive("Type" ),
    tag: grab($Filter, "Tag") ?? \Req::Receive("Tag"),
    order: grab($data, "Order")??\Req::Receive("Order")??["`Priority` DESC,`UpdateTime` DESC"],
    limit: grab($data, "Limit")??grab($Filter, "Limit")??\Req::Receive("Limit")??-1,
    table: grab($data, "Table")
);
?>