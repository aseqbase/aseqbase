<?php
view(grab($data, "View", "ViewName")??\_::$Front->DefaultViewName, ["Name" => grab($data, "View", "Name")??"home",...(grab($data, "View")??[])]);
?>