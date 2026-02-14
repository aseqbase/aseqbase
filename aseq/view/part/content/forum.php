<?php
module("Forum");
$module = new \MiMFa\Module\Forum();
$name = $module->MainClass;
$module->Item = $data;
$module->ExcerptLength = 450;
pod($module, $data);
$module->MainClass = $name;// To do not change the name of module
$module->Render();
?>