<?php
module("Content");
$module = new \MiMFa\Module\Content();
$name = $module->Name;
$module->Item = $data;
swap($module, $data);
$module->Name = $name;// To do not change the name of module
$module->Render();
?>