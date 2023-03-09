<?php
MODULE("PostCollection");
$module = new \MiMFa\Module\PostCollection();
$module->ShowRoute = false;
$module->DefaultImage = \_::$INFO->FullLogoPath;
$module->Items = \MiMFa\Library\DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Content");
$module->Draw();
?>
