<?php

use MiMFa\Library\Convert;
use MiMFa\Library\Html;

$Name = grab($data, 'Name');
$Title = grab($data, 'Title');
module("Navigation");
$nav = new \MiMFa\Module\Navigation(grab($data, "Items"));
module("Collection");
$module = new \MiMFa\Module\Collection();
$module->Title = !isEmpty($Title) && !isEmpty($Name) && abs(strlen($Name) - strlen($Title)) > 3 ? "$Title ".($Name?"($Name)":"") : between($Title, $Name);
$module->DefaultImage = get($data, 'Image');
$module->RootRoute = grab($data, 'RootRoute');
$module->MaximumColumns = 3;
$module->Class .= " page";
$module->Items = $nav->GetItems();
$name = $module->Name;// To do not change the name of module
swap($module, $data);
$module->Name = $name;// To do not change the name of module
$module->Render();
if($module->Items){
    $nav->Render();
    \Res::Render(Html::$BreakLine);
}
part("content/all", ["Items"=>compute("content/all", ["Filter"=>["Category"=>get($data, 'Id')??$Name]])]);