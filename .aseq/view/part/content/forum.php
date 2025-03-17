<?php
module("Forum");
$module = new \MiMFa\Module\Forum();
$name = $module->Name;
$module->Item = $data;
set($module, $data);
$module->Name = $name;// To do not change the name of module
$module->Render();
?>