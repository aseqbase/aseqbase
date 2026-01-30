<?php
return \_::$Back->Query->FindCategory(
    popBetween($data, "Direction", "Name"),
    pop($data, "Default")??[],
    pop($data, "Table")
);
?>