<?php
use MiMFa\Library\Html;

module("SideMenu");
$module = new MiMFa\Module\SideMenu();
$module->Title = \_::$Info->Name;
$module->Description = \_::$Info->Owner;
$module->Items = \_::$Info->SideMenus;
$module->Image = \_::$Info->LogoPath;
$module->Shortcuts = \_::$Info->Contacts;
swap($module, $data);
$module->Render();
\Res::Render(Html::Script("
	function viewSideMenu(show){
		{$module->Name}_ViewSideMenu(show);
	}
"));