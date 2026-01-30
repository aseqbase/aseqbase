<?php
$templ = \_::$Front->CreateTemplate("Main");
$templ->WindowTitle = pop($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' );
$templ->Content = part(
    pop($data, "Part")??"content/all",
    $data,
    alternative: pop($data, "Alternative")??"content/all",
    print:false);
$templ->Render();