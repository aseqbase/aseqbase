<?php
return \_::$Back->Query->FindCategory(
    grabBetween($data, "Direction", "Name"),
    grab($data, "Default")??[],
    grab($data, "Table")
);
?>