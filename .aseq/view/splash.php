<?php 
$templ = \_::$Front->CreateTemplate("Splash");
$templ->WindowTitle = pop($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' )??\_::$Info->FullName;
$templ->WindowLogo = MiMFa\Library\Local::GetUrl(pop($data, "WindowLogo") ??\_::$Info->FullLogoPath??\_::$Info->LogoPath);
$templ->SupTitle = pop($data, "SupTitle") ?? \_::$Info->Owner;
$templ->Title =  pop($data, "Title") ?? \_::$Info->Name;
$templ->SubTitle =  pop($data, "SubTitle") ;
$templ->SupDescription =  pop($data, "SupDescription") ?? "Welcome";
$templ->Description =  pop($data, "Description") ?? \_::$Info->Slogan;
$templ->SubDescription =  pop($data, "SubDescription") ?? \_::$Info->FullSlogan;
$templ->Render();