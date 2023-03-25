<?php
	MODULE("PrePage");
	$module = new MiMFa\Module\PrePage();
	$module->Title = "Contact";
	$module->Draw();
	MODULE("Contacts");
	$module = new MiMFa\Module\Contacts();
	$module->Items = \_::$INFO->Contacts;
	$module->Location = \_::$INFO->Location;
	$module->Draw();
?>