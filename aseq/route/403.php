<?php
$viewData = pop($data, "View");
view(get($viewData, "ViewName")??"message", data: [
    "WindowTitle" => \_::$Front->FullName,
    "WindowLogo" => MiMFa\Library\Storage::GetUrl(\_::$Front->LogoPath),
    "SupTitle" => \_::$Front->Owner,
    "Title" => \_::$Front->Name,
    "SupDescription" => "Forbidden 403",
    "Description" => \_::$Front->RestrictionContent ?? "You don't have permission to access on this server.",
    "SubDescription" => getClientIp(),
    ...$data??[]
]);
?>
