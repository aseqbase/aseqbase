<?php
$Items = grab($data, "Items");
$Name = grab($data, 'Name');
$Title = grab($data, 'Title');
module("Navigation");
$nav = new \MiMFa\Module\Navigation($Items);
module("PostCollection");
$module = new \MiMFa\Module\PostCollection();
$module->Title = !isEmpty($Title) && !isEmpty($Name) && strtolower(preg_replace("/\W*/", "", $Name)) != strtolower(preg_replace("/\W*/", "", $Title)) ? "$Title ".($Name?"($Name)":"") : between($Title, $Name);
$module->DefaultImage = \_::$Info->FullLogoPath;
$module->ShowRoute = false;
$module->Description = grab($data, "Description");
$module->Class .= " page";
$module->Items = $nav->GetItems();
swap($module, $data);
$module->Render();
$nav->Render();
?>