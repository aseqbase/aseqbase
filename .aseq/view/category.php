<?php
$templ = \_::$Front->CreateTemplate("Main");
$templ->WindowTitle = grab($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' );
$templ->Content = part(
    grab($data, "Part")??"category",
    $data,
    alternative: grab($data, "Alternative")??"category",
    print:false);
$templ->Render();