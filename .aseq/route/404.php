<?php
$viewData = pop($data, "View");
view(get($viewData, "ViewName")??\_::$Front->DefaultViewName, data: [
    "WindowTitle" => \_::$Info->FullName,
    "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath),
    "Name" => get($viewData, "Name")??"404",
    ...$data??[]
]);
?>
