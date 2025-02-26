<?php
module("Content" );
$module = new \MiMFa\Module\Content();
$module->Content = isValid(\Req::$Direction)?
	page(normalizePath(\Req::$Direction), alternative:"404", print:false) :
	page("home", alternative:"404", print:false);
swap($module, $data);
$module->Render();
?>