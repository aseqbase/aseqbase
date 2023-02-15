<?php TEMPLATE("Splash");
$templ = new \MiMFa\Template\Splash();
$templ->PageTitle = \_::$INFO->FullName;
$templ->PageLogo = \_::$INFO->LogoPath;
$templ->Logo = \_::$INFO->FullLogoPath;
$templ->SupTitle = \_::$INFO->Owner;
$templ->Title = \_::$INFO->Name;
$templ->Description = \_::$INFO->Slogan;
//$mes->Phrases = \_::$INFO->Slogan;
$templ->Draw();
?>
