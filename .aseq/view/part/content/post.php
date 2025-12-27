<?php
module("Post");
$module = new \MiMFa\Module\Post();
$name = $module->Name;
$module->Item = $data;
pod($module, $data);
$module->Name = $name;// To do not change the name of module
$module->Render();