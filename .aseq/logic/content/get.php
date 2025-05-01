<?php
$Name = grab($data, "Name");
$Filter = grab($data, "Filter");
$result = null;
$cn = \_::$Back->Query->ColumnNames;
\_::$Back->Query->ColumnNames = grab($Filter, "Columns" ) ?? "*";
if(isValid($Name)) $result = \_::$Back->Query->FindContent(
    name: $Name??grab($Filter, "Query"),
    direction: grab($Filter, "Category"),
    type: grab($Filter, "Type" ),
    tag: grab($Filter, "Tag"),
    condition: grab($data, "Condition"),
    params: grab($data, "Params")??[],
    table: grab($data, "Table")
);
\_::$Back->Query->ColumnNames = $cn;
return $result;
?>