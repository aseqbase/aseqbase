<?php TEMPLATE("Message");
$mes = new \MiMFa\Template\Message();
$mes->WindowTitle = \_::$INFO->FullName;
$mes->WindowLogo = MiMFa\Library\Local::GetUrl(\_::$INFO->LogoPath);
$mes->SupTitle = \_::$INFO->Owner;
$mes->Title = \_::$INFO->Name;
$mes->SupDescription = "Authorization Required 401";
$mes->Description = "This server could not verify that you are authorized to access the document requested. Either you supplied the wrong credentials (e.g., bad password), or your browser doesn't understand how to supply the credentials required.";
$mes->Draw();
?>