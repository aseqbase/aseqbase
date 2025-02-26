<?php
view("message", data: [
    "WindowTitle" => \_::$Info->FullName,
    "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath),
    "SupTitle" => \_::$Info->Owner,
    "Title" => \_::$Info->Name,
    "SupDescription" => "Internal Server Error 500",
    "Description" => "The server encountered an internal error or misconfiguration and was unable to complete your request"
]);
?>
