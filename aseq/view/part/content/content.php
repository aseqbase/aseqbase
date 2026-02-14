<?php
module("Content");
$module = new \MiMFa\Module\Content();
$name = $module->MainClass;
$module->Item = $data;
pod($module, $data);
$module->MainClass = $name;// To do not change the name of module
$module->Render();