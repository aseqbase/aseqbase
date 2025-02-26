<?php
$Name = grab($data, "Name")??\Req::Receive("Name" );
$Filter = grab($data, "Filter");
if(isValid($Name)) return \_::$Back->Query->FindContent(
    name: $Name,
    direction: grab($Filter, "Cat")??\Req::Receive("Cat"),
    type: grab($Filter, "Type" ) ?? \Req::Receive("Type" ),
    tag: grab($Filter, "Tag") ?? \Req::Receive("Tag"),
    table: grab($data, "Table")
);
return null;
?>