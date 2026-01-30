<?php
	module("PrePage");
	$module = new MiMFa\Module\PrePage();
	$module->Title = "Contact";
	$module->Render();
	module("Contacts");
	$module = new MiMFa\Module\Contacts();
	$module->Items = \_::$Front->Contacts;
	$module->Location = \_::$Front->Location;
    pod($module, $data);
	$module->Render();
	part("message", $data);
?>