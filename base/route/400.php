<?php
view("message", data: [
    "WindowTitle" => \_::$Front->FullName,
    "WindowLogo" => MiMFa\Library\Storage::GetUrl(\_::$Front->LogoPath),
    "Title" => "Bad Request 400",
    "Description" => "There was an error in your request."
]);
?>