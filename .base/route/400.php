<?php
$viewData = grab($data, "View");
view(get($viewData, "ViewName")??\_::$Config->DefaultViewName, data: [
    "WindowTitle" => \_::$Info->FullName,
    "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath),
    "Name" => get($viewData, "Name")??"400",
    ...($data??[])
]);
/**
 * For a full-screen message
 */
// view("message", data: [
//     "WindowTitle" => \_::$Info->FullName,
//     "WindowLogo" => MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath),
//     "SupTitle" => \_::$Info->Owner,
//     "Title" => \_::$Info->Name,
//     "SupDescription" => "Bad Request 400",
//     "Description" => "There was an error in your request."
// ]);
?>