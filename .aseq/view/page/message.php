<?php
	module("PrePage");
	$module = new MiMFa\Module\PrePage();
	$module->Title = "Message Us";
	$module->Render();
	part("message");
?>