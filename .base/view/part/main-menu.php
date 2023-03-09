<?php MODULE("MainMenu");
$module = new MiMFa\Module\MainMenu();
$module->Title = \_::$INFO->Name;
$module->Description = \_::$INFO->Owner;
$module->Image = \_::$INFO->LogoPath;
$module->Items = \_::$INFO->MainMenus;
$module->Draw();
?>