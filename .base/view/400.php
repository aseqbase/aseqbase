<?php TEMPLATE("Message");
$mes = new \MiMFa\Template\Message();
$mes->WindowTitle = \_::$INFO->FullName;
$mes->WindowLogo = MiMFa\Library\Local::GetUrl(\_::$INFO->LogoPath);
$mes->SupTitle = \_::$INFO->Owner;
$mes->Title = \_::$INFO->Name;
$mes->SupDescription = "Bad Request 400";
$mes->Description = "There was an error in your request.";
$mes->Draw();
?>
