
<?php
$viewData = pop($data, "View");
view(get($viewData, "ViewName")??\_::$Front->DefaultViewName, ["Name" =>get($viewData, "Name")??\_::$Address->UrlRoute, ...($viewData??[])]);
?>
