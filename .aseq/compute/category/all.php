<?php
return \_::$Back->Query->FindCategories(
    popBetween($data, "Direction", "Name"),
    pop($data, "Default")??[],
    pop($data, "Nest")??1,
    pop($data, "Table")
);
?>