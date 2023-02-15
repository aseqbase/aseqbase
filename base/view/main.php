<?php TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
$templ->PageTitle = \_::$INFO->FullName;
$templ->Logo = forceURL(\_::$INFO->LogoPath);
//$mes->Phrases = \_::$INFO->Slogan;
$templ->Draw();
?>
