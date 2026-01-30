<?php
$templ = \_::$Front->CreateTemplate("Template");
$templ->WindowTitle = pop($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' )??\_::$Front->FullName;
$templ->WindowLogo = MiMFa\Library\Local::GetUrl(pop($data, "WindowLogo") ?? \_::$Front->LogoPath);
$templ->Header =  pop($data, "Title") ?? \_::$Front->Name;
$templ->Content = pop($data, "Content") ?? "...";
$templ->Footer = pop($data, "Description") ?? "...";
$templ->Render();