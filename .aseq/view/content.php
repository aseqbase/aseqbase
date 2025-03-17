<?php
$templ = \_::$Front->CreateTemplate("Main");
$templ->WindowTitle = grab($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' );
$templ->Content = part(
    "content/".strtolower(getValid($data, "Type", "post")),
    $data,
    alternative: grab($data, "Alternative")??"content/post",
    print:false);
$templ->Render();
?>