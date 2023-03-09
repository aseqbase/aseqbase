<?php
MODULE("Navigation");
$nav = new MiMFa\Module\Navigation("SELECT * FROM ".\_::$CONFIG->DataBasePrefix."Gallery");
$nav->Draw();

MODULE("Gallery");
$module = new MiMFa\Module\Gallery();
$module->Items =  $nav->GetItems();
$module->Draw();

$nav->Draw();
?>

