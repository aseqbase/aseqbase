<?php
$Filter = grab($data, "Filter");
$cn = \_::$Back->Query->ColumnNames;
\_::$Back->Query->ColumnNames = grab($Filter, "Columns" ) ?? "*";
$result = \_::$Back->Query->SearchContents(
    query: grab($Filter, "Query"),
    direction: grab($Filter, "Category"),
    type: grab($Filter, "Type" ),
    tag: grab($Filter, "Tag"),
    nest: grab($Filter, "Nest")??-1,
    condition: grab($data, "Condition"),
    order: grab($data, "Order")??"`Priority` DESC,`UpdateTime` DESC",
    limit: grab($data, "Limit")??-1,
    params: grab($data, "Params")??[],
    table: grab($data, "Table")
);
\_::$Back->Query->ColumnNames = $cn;
return $result;
?>