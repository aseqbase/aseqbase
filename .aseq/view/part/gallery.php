<?php
module("Navigation");
$nav = new MiMFa\Module\Navigation("SELECT * FROM ".table("Gallery")->Name);
$nav->Render();

module("Gallery");
$module = new MiMFa\Module\Gallery();
$module->Items =  $nav->GetItems();
$module->Render();

$nav->Render();
?>

