<?php 
$templ = \_::$Front->CreateTemplate("Splash");
$templ->WindowTitle = grab($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' )??\_::$Info->FullName;
$templ->WindowLogo = MiMFa\Library\Local::GetUrl(grab($data, "WindowLogo") ??\_::$Info->FullLogoPath??\_::$Info->LogoPath);
$templ->SupTitle = grab($data, "SupTitle") ?? \_::$Info->Owner;
$templ->Title =  grab($data, "Title") ?? \_::$Info->Name;
$templ->SubTitle =  grab($data, "SubTitle") ;
$templ->SupDescription =  grab($data, "SupDescription") ?? "Welcome";
$templ->Description =  grab($data, "Description") ?? \_::$Info->Slogan;
$templ->SubDescription =  grab($data, "SubDescription") ?? \_::$Info->FullSlogan;
$templ->Render();
?>
