<?php
module("Post");
$module = new \MiMFa\Module\Post();
set($module, $data);
$module->Item = $data;
$module->Render();
?>