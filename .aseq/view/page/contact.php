<?php
	module("PrePage");
	$module = new MiMFa\Module\PrePage();
	$module->Title = "Contact";
	$module->Render();
	module("Contacts");
	$module = new MiMFa\Module\Contacts();
	$module->Items = \_::$Info->Contacts;
	$module->Location = \_::$Info->Location;
    swap($module, $data);
	$module->Render();
	part("message", $data);
?>