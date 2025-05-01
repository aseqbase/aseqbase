<?php
$name = \Req::ReceivePost("Name");
if(empty($name)) return;
$name = NormalizePath($name);
page($name);
?>