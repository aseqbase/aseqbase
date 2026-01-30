<?php
module("Article");
$module = new \MiMFa\Module\Article();
$name = $module->Name;
$module->Item = $data;
pod($module, $data);
$module->Name = $name;// To do not change the name of module
$module->Render();