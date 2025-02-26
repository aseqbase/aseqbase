<?php
$templ = \_::$Front->CreateTemplate("Main");
$templ->WindowTitle = grab($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' );
$Name = grab($data, "Name") ?? \Req::$Direction;
$alternative = grab($data, "Alternative") ?? "404";
$Content = grab($data, "Content");
$templ->Content = isValid($Content)?
        \MiMFa\Library\Html::Page($Content):
        page($Name, data: $data, print:false, alternative: $alternative);
$templ->Render();
?>