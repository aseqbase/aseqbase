<?php
$viewData = grab($data, "View");
view(get($viewData, "ViewName")??"message", data: [
    "WindowTitle" => \_::$Info->FullName,
    "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath),
    "Title" => "Forbidden 403",
    "Description" => \_::$Config->RestrictionContent ?? "You don't have permission to access on this server.",
    "Content" => getClientIp(),
    ...($data??[])
]);
?>
