<?php
$templ = \_::$Front->CreateTemplate("Main");
$templ->WindowTitle = pop($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' );
$templ->Content = part(
    pop($data, "Part")??"category",
    $data,
    alternative: pop($data, "Alternative")??"category",
    print:false);
$templ->Render();