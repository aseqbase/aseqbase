<?php
module("RingTabs");
$module = new \MiMFa\Module\RingTabs();
$module->Image = \_::$Front->FullLogoPath;
$module->Items = \_::$Front->Services;
pod($module, $data);
$module->Render();
?>