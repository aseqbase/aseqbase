<?php module("MainMenu");
$module = new MiMFa\Module\MainMenu();
$module->Title = \_::$Info->Name;
$module->Description = \_::$Info->Owner;
$module->Image = \_::$Info->LogoPath;
$module->Items = \_::$Info->MainMenus;
if (\_::$Config->AllowTranslate) {
    $moduleTranslator = new (module("Translator"))();
    $moduleTranslator->Items = \_::$Back->Translate->GetLanguages();
    $module->Content = $moduleTranslator->ToString();
}
$module->AllowItemsDescription =
$module->AllowSubItemsDescription = 
$module->AllowSubItemsImage = 
$module->AllowItemsImage = false;
$module->AllowFixed = false;
swap($module, $data);
$module->Render();