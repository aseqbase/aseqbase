<?php
$templ = \_::$Front->CreateTemplate("Main");
$name = grab($data, "Name") ?? \Req::$Direction;
$templ->WindowTitle = grab($data, "WindowTitle")??get($data, 'Title' )??between($name, \_::$Info->Name);
$alternative = grab($data, "Alternative") ?? "404";
$content = grab($data, "Content");
$templ->Content = isValid($content)?
        \MiMFa\Library\Html::Page($content):
        function() use($name, $data, $alternative) { return page($name, data: $data, print:false, alternative: $alternative); };
$templ->Render();
?>