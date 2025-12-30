<?php
view("message", data: [
    "WindowTitle" => \_::$Front->FullName,
    "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Front->LogoPath),
    "Title" => "HTTP 404 Not Found",
    "Description" => "The requested URL was not found on this server."
]);
?>
