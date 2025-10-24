<?php
$templ = \_::$Front->CreateTemplate("Message");
$templ->WindowTitle = pop($data, "WindowTitle")??get($data, 'Title' )??get($data, 'Name' )??\_::$Info->FullName;
$templ->WindowLogo = MiMFa\Library\Local::GetUrl(pop($data, "WindowLogo") ?? \_::$Info->LogoPath);
$templ->SupTitle = pop($data, "SupTitle") ?? \_::$Info->Owner;
$templ->Title =  $pop($data, "Title") ?? \_::$Info->Name;
$templ->SubTitle =  pop($data, "SubTitle");
$templ->SupDescription =  pop($data, "SupDescription") ?? "A Message";
$templ->Description = pop($data, "Description") ?? "...";
$templ->SubDescription =  pop($data, "SubDescription");
$templ->Render();