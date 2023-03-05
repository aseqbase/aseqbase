<?php
MODULE("PrePage");
$module= new MiMFa\Module\PrePage();
$module->Title = "Features";
$module->Description = MiMFa\Library\Style::DoStrong("Our software includes the options and utilities as follows");
$module->Draw();

PART("future");
?>