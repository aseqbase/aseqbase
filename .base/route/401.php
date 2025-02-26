<?php
view("main", data: [
    "WindowTitle" => \_::$Info->FullName,
    "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath),
    "Name" => "403"
]);
/**
 * For a full-screen message
 */
// view("message", data: [
//     "WindowTitle" => \_::$Info->FullName,
//     "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath),
//     "SupTitle" => \_::$Info->Owner,
//     "Title" => \_::$Info->Name,
//     "SupDescription" => "Authorization Required 401",
//     "Description" => "This server could not verify that you are authorized to access the document requested. Either you supplied the wrong credentials (e.g., bad password), or your browser doesn't understand how to supply the credentials required."
// ]);
?>
