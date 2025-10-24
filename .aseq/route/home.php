<?php
view(pop($data, "View", "ViewName")??\_::$Front->DefaultViewName, ["Name" => pop($data, "View", "Name")??"home",...(pop($data, "View")??[])]);
?>