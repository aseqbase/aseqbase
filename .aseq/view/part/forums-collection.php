<?php
use MiMFa\Library\DataBase;
use MiMFa\Library\User;
MODULE("Navigation");
$nav = new \MiMFa\Module\Navigation(DataBase::MakeSelectQuery(\_::$CONFIG->DataBasePrefix."Content","*",
    "`Type`=\"Forum\" AND ".User::GetAccessCondition()." ORDER BY `Priority` DESC, `UpdateTime` DESC"));
MODULE("PostCollection");
$module = new \MiMFa\Module\PostCollection();
$module->Root = "/forum/";
$module->Style = new \MiMFa\Library\Style();
$module->ShowRoute = false;
$module->DefaultImage = \_::$INFO->FullLogoPath;
$module->Items = $nav->GetItems();

$module->Draw();
$nav->Draw();
?>