
<?php
$viewData = grab($data, "View");
view(get($viewData, "ViewName")??\_::$Config->DefaultViewName, ["Name" =>get($viewData, "Name")??\_::$Base->Direction, ...($viewData??[])]);
?>
