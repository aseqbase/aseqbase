<?php
use MiMFa\Library\DataBase;
use MiMFa\Library\User;
MODULE("Navigation");
$nav = new \MiMFa\Module\Navigation(DataBase::MakeSelectQuery(\_::$CONFIG->DataBasePrefix."Content","*",
    User::GetAccessCondition()." ORDER BY `Priority` DESC, `UpdateTime` DESC"));
MODULE("PostCollection");
$module = new \MiMFa\Module\PostCollection();
$module->Style = new \MiMFa\Library\Style();
$module->Style->Padding = "var(--Size-5)";
$module->ShowRoute = false;
$module->DefaultImage = \_::$INFO->FullLogoPath;
$module->Items = $nav->GetItems();

$module->Draw();
$nav->Draw();
?>