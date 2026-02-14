<?php
$Items = pop($data, "Items");
$Name = pop($data, 'Name');
$Title = pop($data, 'Title');
module("Navigation");
$nav = new \MiMFa\Module\Navigation($Items);
module("ContentCollection");
$module = new \MiMFa\Module\ContentCollection();
$name = $module->MainClass;
$module->Title = between($Title, $Name);//!isEmpty($Title) && !isEmpty($Name) && abs(strlen($Name) - strlen($Title)) > 3 ? "$Title ".($Name?"($Name)":"") : between($Title, $Name);
$module->Description = pop($data, "Description");
$module->DefaultImage = pop($data, 'Image');
$module->AllowRoot = false;
$module->Class .= " page";
$module->Items = $nav->GetItems();
pod($module, $data);
$module->MainClass = $name;// To do not change the name of module
$module->Render();
$nav->Render();
?>