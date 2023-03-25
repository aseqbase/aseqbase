<?php
MODULE("PrePage");
$module = new \MiMFa\Module\PrePage();
$module->Title = "HTTP 404 Not Found";
$module->Image = \MiMFa\Library\Local::GetUrl("file/general/error.png");
$module->Description = "Not Found: The requested URL was not found on this server.";
$module->Draw();
?>