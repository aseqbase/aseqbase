<?php
$templ = \_::$Front->CreateTemplate("Main");
$templ->WindowTitle = pop($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' );
$templ->Content = part(
    pop($data, "Part")??"content/".strtolower(getValid($data, "Type", "content")),
    $data,
    alternative: pop($data, "Alternative")??"content/content",
    print:false);
$templ->Render();