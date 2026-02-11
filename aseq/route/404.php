<?php
$viewData = pop($data, "View");
view(get($viewData, "ViewName")??\_::$Front->DefaultViewName, data: [
    "WindowTitle" => \_::$Front->FullName,
    "WindowLogo" => MiMFa\Library\Storage::GetUrl(\_::$Front->LogoPath),
    "Name" => get($viewData, "Name")??"404",
    ...$data??[]
]);
?>
