<?php
module("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Title =  "Internal Server Error 500";
$module->Image = MiMFa\Library\Local::GetUrl(\_::$Front->ErrorSymbolPath);
$module->Description = "The server encountered an internal error or misconfiguration and was unable to complete your request";
pod($module, $data);
$module->Render();
?>