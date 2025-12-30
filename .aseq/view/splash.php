<?php 
$templ = \_::$Front->CreateTemplate("Splash");
$templ->WindowTitle = pop($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' )??\_::$Front->FullName;
$templ->WindowLogo = MiMFa\Library\Local::GetUrl(pop($data, "WindowLogo") ??\_::$Front->FullLogoPath??\_::$Front->LogoPath);
$templ->SupTitle = pop($data, "SupTitle") ?? \_::$Front->Owner;
$templ->Title =  pop($data, "Title") ?? \_::$Front->Name;
$templ->SubTitle =  pop($data, "SubTitle") ;
$templ->SupDescription =  pop($data, "SupDescription") ?? "Welcome";
$templ->Description =  pop($data, "Description") ?? \_::$Front->Slogan;
$templ->SubDescription =  pop($data, "SubDescription") ?? \_::$Front->FullSlogan;
$templ->Render();