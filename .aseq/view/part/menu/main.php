<?php module("MainMenu");
$module = new MiMFa\Module\MainMenu();
$module->Title = \_::$Info->Product;
$module->Description = \_::$Info->Owner;
$module->Image = \_::$Info->LogoPath;
$module->Items = \_::$Info->MainMenus;
swap($module, $data);
$module->Render();
?>