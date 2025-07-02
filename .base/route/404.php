<?php
view("message", data: [
    "WindowTitle" => \_::$Info->FullName,
    "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath),
    "Title" => "HTTP 404 Not Found",
    "Description" => "The requested URL was not found on this server."
]);
?>
