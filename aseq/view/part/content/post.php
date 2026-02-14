<?php
module("Post");
$module = new \MiMFa\Module\Post();
$name = $module->MainClass;
$module->Item = $data;
pod($module, $data);
$module->MainClass = $name;// To do not change the name of module
$module->Render();