<?php
	module("PrePage");
	$module = new MiMFa\Module\PrePage();
	$module->Title = "Gallery";
    swap($module, $data);
	$module->Render();
	part("gallery");
?>
