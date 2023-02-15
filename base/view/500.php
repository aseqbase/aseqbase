<?php TEMPLATE("Message");
$mes = new \MiMFa\Template\Message();
$mes->PageTitle = \_::$INFO->FullName;
$mes->Logo = \_::$INFO->LogoPath;
$mes->SupTitle = \_::$INFO->Owner;
$mes->Title = \_::$INFO->Name;
$mes->SupDescription = "Internal Server Error 500";
$mes->Description = "The server encountered an internal error or misconfiguration and was unable to complete your request";
$mes->Draw();
?>