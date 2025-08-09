<?php
$viewData = grab($data, "View");
view(get($viewData, "ViewName")??\_::$Config->DefaultViewName, data: [
    "WindowTitle" => \_::$Info->FullName,
    "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath),
    "Name" => get($viewData, "Name")??"404",
    ...$data??[]
]);
?>
