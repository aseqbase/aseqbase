<?php
$viewData = pop($data, "View");
view(get($viewData, "ViewName")??"message", data: [
    "WindowTitle" => \_::$Front->FullName,
    "WindowLogo" => MiMFa\Library\Storage::GetUrl(\_::$Front->LogoPath),
    "Title" => "Internal Server Error 500",
    "Description" => "The server encountered an internal error or misconfiguration and was unable to complete your request",
    ...($data??[])
]);
?>
