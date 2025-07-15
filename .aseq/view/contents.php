<?php
$templ = \_::$Front->CreateTemplate("Main");
$templ->WindowTitle = grab($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' );
$templ->Content = part(
    grab($data, "Part")??"content/all",
    $data,
    alternative: grab($data, "Alternative")??"content/all",
    print:false);
$templ->Render();