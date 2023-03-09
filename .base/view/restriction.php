<?php TEMPLATE("Message");
$mes = new \MiMFa\Template\Message();
$mes->WindowTitle = \_::$INFO->FullName;
$mes->WindowLogo = \_::$INFO->FullLogoPath;
$mes->SupTitle = \_::$INFO->Owner;
$mes->Title = \_::$INFO->Name;
$mes->Description = \_::$CONFIG->RestrictionContent;
$mes->SubDescription =  GetClientIP();
$mes->Draw();
?>