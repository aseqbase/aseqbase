<?php
return \_::$Back->Query->FindTag(
    pop($data, "Name"),
    pop($data, "Default")??[],
    pop($data, "Table")
);
?>