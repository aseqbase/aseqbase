<?php
$templ = \_::$Front->CreateTemplate("Template");
$templ->WindowTitle = grab($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' )??\_::$Info->FullName;
$templ->WindowLogo = MiMFa\Library\Local::GetUrl(grab($data, "WindowLogo") ?? \_::$Info->LogoPath);
$templ->Header =  grab($data, "Title") ?? \_::$Info->Name;
$templ->Content = grab($data, "Content") ?? "...";
$templ->Footer = grab($data, "Description") ?? "...";
$templ->Render();
?>