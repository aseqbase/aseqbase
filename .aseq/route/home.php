<?php
view(grab($data, "View", "ViewName")??\_::$Config->DefaultViewName, ["Name" => grab($data, "View", "Name")??"home",...(grab($data, "View")??[])]);
?>