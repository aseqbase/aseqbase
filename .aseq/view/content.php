<?php
$templ = \_::$Front->CreateTemplate("Main");
$templ->WindowTitle = grab($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' );
$templ->Content = part(
    grab($data, "Part")??"content/".strtolower(getValid($data, "Type", "content")),
    $data,
    alternative: grab($data, "Alternative")??"content/content",
    print:false);
$templ->Render();
?>