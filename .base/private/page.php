<?php
$name = $_POST["name"];
if(empty($name)) return;
$name = NormalizePath($name);
PAGE($name);
?>