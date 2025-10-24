<?php
$Filter = pop($data, "Filter");
$cn = \_::$Back->Query->ColumnNames;
\_::$Back->Query->ColumnNames = pop($Filter, "Columns" ) ?? "*";
$result = \_::$Back->Query->SearchContents(
    query: pop($Filter, "Query"),
    direction: popBetween($Filter, "Category", "Direction"),
    type: pop($Filter, "Type" ),
    tag: pop($Filter, "Tag"),
    nest: pop($Filter, "Nest")??-1,
    condition: pop($data, "Condition"),
    order: pop($data, "Order")??"`Priority` DESC,`UpdateTime` DESC",
    limit: pop($data, "Limit")??-1,
    params: pop($data, "Params")??[],
    table: pop($data, "Table")
);
\_::$Back->Query->ColumnNames = $cn;
return $result;
?>