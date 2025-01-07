<?php
if(!isView()){
	MODULE("PrePage");
	$module = new MiMFa\Module\PrePage();
	$module->Title = "Message Us";
	$module->Draw();
}
	PART("message");
?>