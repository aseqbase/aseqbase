<?php
	MODULE("PrePage");
	$module = new MiMFa\Module\PrePage();
	$module->Title = "Forums";
	$module->Draw();

	PART("forums-collection");
?>

