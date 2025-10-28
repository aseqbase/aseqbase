
<?php
module("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Title = "Forbidden 403";
$module->Image = MiMFa\Library\Local::GetUrl(\_::$Info->ErrorSymbolPath);
$module->Description = \_::$Router->RestrictionContent ?? "You don't have permission to access on this server.";
pod($module, $data);
$module->Render();
?>