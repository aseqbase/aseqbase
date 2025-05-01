<?php
$Items = grab($data, "Items");
$Name = grab($data, 'Name');
$Title = grab($data, 'Title');
module("Navigation");
$nav = new \MiMFa\Module\Navigation($Items);
module("ContentCollection");
$module = new \MiMFa\Module\ContentCollection();
$name = $module->Name;
$module->Title = !isEmpty($Title) && !isEmpty($Name) && abs(strlen($Name) - strlen($Title)) > 3 ? "$Title ".($Name?"($Name)":"") : between($Title, $Name);
$module->Description = grab($data, "Description");
$module->DefaultImage = grab($data, 'Image');
$module->ShowRoute = false;
$module->Class .= " page";
$module->Items = $nav->GetItems();
swap($module, $data);
$module->Name = $name;// To do not change the name of module
$module->Render();
$nav->Render();
?>