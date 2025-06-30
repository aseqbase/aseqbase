<?php
return \_::$Back->Query->FindTag(
    grab($data, "Name"),
    grab($data, "Default")??[],
    grab($data, "Table")
);
?>