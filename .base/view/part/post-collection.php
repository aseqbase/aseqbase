<?php
MODULE("PostCollection");
$module = new \MiMFa\Module\PostCollection();
$module->Style = new \MiMFa\Library\Style();
$module->Style->Padding = "var(--Size-5)";
$module->ShowRoute = false;
$module->DefaultImage = \_::$INFO->FullLogoPath;
$module->Items = \MiMFa\Library\DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Content");
$module->Draw();
?>
