<?php
view(grab($data, "View", "ViewName")??"part", ["Name"=>grab($data, "View", "Name")??"sign/view"]);
?>