<?php
module("Page");
$module = new \MiMFa\Module\Page();
$Name = grab($data, "Name");
$module->Content =
	isValid($Name) ?
	page($Name, alternative: "404", print: false) : (
		isValid(\Req::$Direction) ?
		page(normalizePath(\Req::$Direction), alternative: "404", print: false) :
		page("home", alternative: "404", print: false)
	);
swap($module, $data);
$module->Render();
?>