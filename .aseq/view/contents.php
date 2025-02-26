<?php
$templ = \_::$Front->CreateTemplate("Main");
$templ->WindowTitle = grab($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' );
$templ->Content =  part(
    "content/all",
    $data,
    print:false);
$templ->Render();
?>