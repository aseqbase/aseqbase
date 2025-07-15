<?php module("MainMenu");
$module = new MiMFa\Module\MainMenu();
$module->Title = \_::$Info->Name;
$module->Description = \_::$Info->Owner;
$module->Image = \_::$Info->LogoPath;
$module->Items = \_::$Info->MainMenus;
$module->AllowFixed = true;
swap($module, $data);
$module->Render();