<?php
$templ = \_::$Front->CreateTemplate("Main");
$templ->WindowTitle = pop($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' );
$Name = pop($data, "Name");
module("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Title = pop($data, 'Title');
$module->Description = pop($data, 'Description');
$module->Content = pop($data, 'Content');
$module->Image = pop($data, 'Image');
$alternative = pop($data, "Alternative")??404;
if ($Name) $templ->Content = ($module->Title || $module->Description || $module->Content || $module->Image?$module->Handle():"").\MiMFa\Library\Html::Page(part($Name, data: $data, print: false)??view($alternative, data: $data, print: false));
else $templ->Content = $module->Handle().view($alternative, data: $data, print: false);
$templ->Render();