<?php
module("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Title = "Authorization Required 401";
$module->Image = MiMFa\Library\Local::GetUrl(\_::$Info->ErrorSymbolPath);
$module->Description = "This server could not verify that you are authorized to access the document requested. Either you supplied the wrong credentials (e.g., bad password), or your browser doesn't understand how to supply the credentials required.";
swap($module, $data);
$module->Render();
?>