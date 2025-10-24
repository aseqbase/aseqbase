<?php
$viewData = pop($data, "View");
view(get($viewData, "ViewName")??"message", data: [
    "WindowTitle" => \_::$Info->FullName,
    "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath),
    "SupTitle" => \_::$Info->Owner,
    "Title" => \_::$Info->Name,
    "SupDescription" => "Forbidden 403",
    "Description" => \_::$Router->RestrictionContent ?? "You don't have permission to access on this server.",
    "SubDescription" => getClientIp(),
    ...$data??[]
]);
?>
