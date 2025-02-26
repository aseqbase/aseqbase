<?php
$name = \Req::Post("Name");
if(empty($name)) return;
$name = NormalizePath($name);
page($name);
?>