<?php
$templ = \_::$Front->CreateTemplate("Message");
$templ->WindowTitle = grab($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' )??\_::$Info->FullName;
$templ->WindowLogo = MiMFa\Library\Local::GetUrl(grab($data, "WindowLogo") ?? \_::$Info->LogoPath);
$templ->SupTitle = grab($data, "SupTitle") ?? \_::$Info->Owner;
$templ->Title =  $grab($data, "Title") ?? \_::$Info->Name;
$templ->SubTitle =  grab($data, "SubTitle");
$templ->SupDescription =  grab($data, "SupDescription") ?? "A Message";
$templ->Description = grab($data, "Description") ?? "...";
$templ->SubDescription =  grab($data, "SubDescription");
$templ->Render();