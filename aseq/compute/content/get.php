<?php
$Name = pop($data, "Name");
$Filter = pop($data, "Filter");
$result = null;
$cn = \_::$Back->Query->ColumnNames;
\_::$Back->Query->ColumnNames = pop($Filter, "Columns" ) ?? "*";
if(isValid($Name)) $result = \_::$Back->Query->FindContent(
    name: $Name??pop($Filter, "Query"),
    direction: pop($Filter, "Category"),
    type: pop($Filter, "Type" ),
    tag: pop($Filter, "Tag"),
    condition: pop($data, "Condition"),
    params: pop($data, "Params")??[],
    table: pop($data, "Table")
);
\_::$Back->Query->ColumnNames = $cn;
return $result;
?>