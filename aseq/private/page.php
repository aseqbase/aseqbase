<?php
$name = receivePost("Name");
if(empty($name)) return;
$name = NormalizePath($name);
page($name);
?>