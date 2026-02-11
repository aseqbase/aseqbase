<?php
module("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Title = "Bad Request 400";
$module->Image = MiMFa\Library\Storage::GetUrl(\_::$Front->ErrorSymbolPath);
$module->Description = "There was an error in your request.";
pod($module, $data);
$module->Render();
?>