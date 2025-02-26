<?php
view("message", data: [
    "WindowTitle" => \_::$Info->FullName,
    "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath),
    "SupTitle" => \_::$Info->Owner,
    "Title" => \_::$Info->Name,
    "SupDescription" => "Forbidden 403",
    "Description" => \_::$Config->RestrictionContent ?? "You don't have permission to access on this server.",
    "SubDescription" => GetClientIp()
]);
?>
