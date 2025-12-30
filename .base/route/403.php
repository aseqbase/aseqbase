<?php
$viewData = pop($data, "View");
view(get($viewData, "ViewName")??"message", data: [
    "WindowTitle" => \_::$Front->FullName,
    "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Front->LogoPath),
    "Title" => "Forbidden 403",
    "Description" => \_::$Router->RestrictionContent ?? "You don't have permission to access on this server.",
    "Content" => getClientIp(),
    ...($data??[])
]);
?>
