<?php TEMPLATE("Message");
$mes = new \MiMFa\Template\Message();
$mes->PageTitle = \_::$INFO->FullName;
$mes->Logo = \_::$INFO->LogoPath;
$mes->SupTitle = \_::$INFO->Owner;
$mes->Title = \_::$INFO->Name;
$mes->SupDescription = "HTTP 404 Not Found";
$mes->Description = "Not Found: The requested URL was not found on this server.";
$mes->Draw();
?>