<?php
return \_::$Back->Query->FindCategory(
    grab($data, "Name"),
    grab($data, "Default")??[],
    grab($data, "Table")
);
?>