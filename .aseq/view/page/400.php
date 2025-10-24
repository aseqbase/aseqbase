<?php
module("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Title = "Bad Request 400";
$module->Image = MiMFa\Library\Local::GetUrl(\_::$Info->ErrorSymbolPath);
$module->Description = "There was an error in your request.";
dip($module, $data);
$module->Render();
?>