<?php
$templ = \_::$Front->CreateTemplate("Template");
$templ->WindowTitle = pop($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' )??\_::$Info->FullName;
$templ->WindowLogo = MiMFa\Library\Local::GetUrl(pop($data, "WindowLogo") ?? \_::$Info->LogoPath);
$templ->Header =  pop($data, "Title") ?? \_::$Info->Name;
$templ->Content = pop($data, "Content") ?? "...";
$templ->Footer = pop($data, "Description") ?? "...";
$templ->Render();