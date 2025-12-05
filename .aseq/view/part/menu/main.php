<?php
 module("MainMenu");
$module = new MiMFa\Module\MainMenu();
$module->Title = \_::$Info->Name;
$module->Description = \_::$Info->Owner;
$module->Image = \_::$Info->LogoPath;
$module->Items = \_::$Info->MainMenus;
if (\_::$Front->AllowTranslate) {
    $moduleTranslator = new (module("Translator"))();
    $moduleTranslator->Items = \_::$Front->Translate->GetLanguages();
    $moduleTranslator->Style = new MiMFa\Library\Style();
	$moduleTranslator->Style->Padding = "0px var(--size-2)";
    $module->Content = $moduleTranslator->ToString();
}
$module->AllowItemsDescription =
$module->AllowSubItemsDescription = 
$module->AllowSubItemsImage = 
$module->AllowItemsImage = false;
$module->AllowFixed = false;
pod($module, $data);
$module->Render();