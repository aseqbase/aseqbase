<?php
$templ = \_::$Front->CreateTemplate("Main");
$templ->WindowTitle = grab($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' );
$Name = grab($data, "Name");
module("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Title = get($data, 'Title');
$module->Description = get($data, 'Description');
$module->Content = get($data, 'Content');
$module->Image = get($data, 'Image');
$alternative = get($data, "Alternative")??404;
if ($Name) $templ->Content = $module->Handle().\MiMFa\Library\Html::Page(part($Name, data: $data, print: false)??view($alternative, data: $data, print: false));
else $templ->Content = $module->Handle().view($alternative, data: $data, print: false);
$templ->Render();
?>