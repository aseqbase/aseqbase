<?php
module("RingTabs");
$module = new \MiMFa\Module\RingTabs();
$module->Image = \_::$Info->FullLogoPath;
$module->Items = \_::$Info->Services;
swap($module, $data);
$module->Render();
?>