<?php
	module("PrePage");
	$module = new MiMFa\Module\PrePage();
	$module->Title = "Gallery";
	$module->Render();

	part("gallery");
?>
