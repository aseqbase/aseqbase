<?php
use MiMFa\Library\Struct;

module("SideMenu");
$module = new MiMFa\Module\SideMenu();
$module->Title = \_::$Front->Name;
$module->Description = \_::$Front->Owner;
$module->Items = \_::$Front->SideMenus;
$module->Image = \_::$Front->LogoPath;
$module->Shortcuts = \_::$Front->Contacts;
if (\_::$Front->AllowTranslate) {
    $moduleTranslator = new (module("Translator"))();
    $moduleTranslator->Items = \_::$Front->Translate->GetLanguages();
    $moduleTranslator->AllowLabel = true;
    $moduleTranslator->AllowImage = false;
    $moduleTranslator->Style = new MiMFa\Library\Style();
	$moduleTranslator->Style->Padding = "var(--size-2)  var(--size-min)";
    $module->Content = $moduleTranslator->ToString();
}
pod($module, $data);
$module->Render();
response(Struct::Script("
	function viewSideMenu(show){
		{$module->MainClass}_ViewSideMenu(show);
	}
"));