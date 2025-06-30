<?php
return \_::$Back->Query->FindCategories(
    grabBetween($data, "Direction", "Name"),
    grab($data, "Default")??[],
    1,
    grab($data, "Table")
);
?>