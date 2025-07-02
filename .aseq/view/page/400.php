<?php
module("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Title = "Bad Request 400";
$module->Image = MiMFa\Library\Local::GetUrl("asset/general/error.png");
$module->Description = "There was an error in your request.";
swap($module, $data);
$module->Render();
?>