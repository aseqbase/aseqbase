<?php
view("main", data: [
    "WindowTitle" => \_::$Info->FullName,
    "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath),
    "Name" => "404"
]);
/**
 * For a full-screen message
 */
// view("message", data: [
//     "WindowTitle" => \_::$Info->FullName,
//     "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath),
//     "SupTitle" => \_::$Info->Owner,
//     "Title" => \_::$Info->Name,
//     "SupDescription" => "HTTP 404 Not Found",
//     "Description" => "The requested URL was not found on this server."
// ]);
?>
