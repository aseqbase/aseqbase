<?php
use MiMFa\Library\Convert;
use MiMFa\Library\Struct;

module("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Title = pop($data, "Title");
$module->Image = pop($data, "Image");
$module->Description = pop($data, "Description");
$content = pop($data, "Content");
pod($module, $data);
response(Struct::Page(
	$module->Handle() .
	Convert::ToString($content)
));