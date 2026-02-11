
<?php
module("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Title = "Forbidden 403";
$module->Image = MiMFa\Library\Storage::GetUrl(\_::$Front->ErrorSymbolPath);
$module->Description = \_::$Front->RestrictionContent ?? "You don't have permission to access on this server.";
pod($module, $data);
$module->Render();
?>