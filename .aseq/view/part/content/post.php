<?php
module("Post");
$module = new \MiMFa\Module\Post();
grab($data, "Name");// To do not change the name of module
$module->Item = $data;
set($module, $data);
$module->Render();
?>