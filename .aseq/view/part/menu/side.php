<?php
use MiMFa\Library\Html;

module("SideMenu");
$module = new MiMFa\Module\SideMenu();
$module->Title = \_::$Info->Name;
$module->Description = \_::$Info->Owner;
$module->Items = \_::$Info->SideMenus;
$module->Image = \_::$Info->LogoPath;
$module->Shortcuts = \_::$Info->Contacts;
if (\_::$Back->AllowTranslate) {
    $moduleTranslator = new (module("Translator"))();
    $moduleTranslator->Items = \_::$Back->Translate->GetLanguages();
    $moduleTranslator->AllowLabel = true;
    $moduleTranslator->AllowImage = false;
    $moduleTranslator->Style = new MiMFa\Library\Style();
	$moduleTranslator->Style->Padding = "var(--size-2)  var(--size-min)";
    $module->Content = $moduleTranslator->ToString();
}
pod($module, $data);
$module->Render();
response(Html::Script("
	function viewSideMenu(show){
		{$module->Name}_ViewSideMenu(show);
	}
"));