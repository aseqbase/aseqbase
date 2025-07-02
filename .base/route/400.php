<?php
view("message", data: [
    "WindowTitle" => \_::$Info->FullName,
    "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath),
    "Title" => "Bad Request 400",
    "Description" => "There was an error in your request."
]);
?>