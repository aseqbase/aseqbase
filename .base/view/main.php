<?php
$templ = \_::$Front->CreateTemplate("Template");
$name = pop($data, "Name") ?? \_::$Address->Direction;
$templ->WindowTitle = pop($data, "WindowTitle")??get($data, 'Title' )??between($name, \_::$Info->Name);
$alternative = pop($data, "Alternative") ?? "404";
$content = pop($data, "Content");
$templ->Content = isValid($content)?
        \MiMFa\Library\Html::Page($content):
        function() use($name, $data, $alternative) { return page($name, data: $data, print:false, alternative: $alternative); };
$templ->Render();