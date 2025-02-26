
<?php
module("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Title = "Forbidden 403";
$module->Image = MiMFa\Library\Local::GetUrl("asset/general/error.png");
$module->Description = \_::$Config->RestrictionContent ?? "You don't have permission to access on this server.";
$module->Render();
?>