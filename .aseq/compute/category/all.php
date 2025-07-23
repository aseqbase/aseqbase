<?php
return \_::$Back->Query->FindCategories(
    grabBetween($data, "Direction", "Name"),
    grab($data, "Default")??[],
    grab($data, "Nest")??1,
    grab($data, "Table")
);
?>