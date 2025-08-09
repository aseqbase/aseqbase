<?php
module("PrePage");
$module = new \MiMFa\Module\PrePage();
$module->Title = "HTTP 404 Not Found";
$module->Image = \MiMFa\Library\Local::GetUrl(\_::$Info->ErrorSymbolPath);
$module->Description = "The requested URL was not found on this server.";
swap($module, $data);
$module->Render();
?>