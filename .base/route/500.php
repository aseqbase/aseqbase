<?php
$viewData = grab($data, "View");
view(get($viewData, "ViewName")??"message", data: [
    "WindowTitle" => \_::$Info->FullName,
    "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath),
    "Title" => "Internal Server Error 500",
    "Description" => "The server encountered an internal error or misconfiguration and was unable to complete your request",
    ...($data??[])
]);
?>
