<?php
$Items = pop($data, "Items");
$Name = pop($data, 'Name');
$Title = pop($data, 'Title');
module("Navigation");
$nav = new \MiMFa\Module\Navigation($Items);
module("ContentCollection");
$module = new \MiMFa\Module\ContentCollection();
$name = $module->Name;
$module->Title = !isEmpty($Title) && !isEmpty($Name) && abs(strlen($Name) - strlen($Title)) > 3 ? "$Title ".($Name?"($Name)":"") : between($Title, $Name);
$module->Description = pop($data, "Description");
$module->DefaultImage = pop($data, 'Image');
$module->AllowRoot = false;
$module->Class .= " page";
$module->Items = $nav->GetItems();
dip($module, $data);
$module->Name = $name;// To do not change the name of module
$module->Render();
$nav->Render();
?>