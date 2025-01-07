<?php
use MiMFa\Library\HTML;

if (!isView()) {
	MODULE("PrePage");
	$module = new MiMFa\Module\PrePage();
	$module->Title = "Payment";
	echo HTML::Page($module->Capture(), ["class" => "page-pay"]);
}
PART("pay");
?>