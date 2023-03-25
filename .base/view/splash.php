<?php TEMPLATE("Splash");
$templ = new \MiMFa\Template\Splash();
$templ->WindowTitle = \_::$INFO->FullName;
$templ->WindowLogo = \_::$INFO->LogoPath;
$templ->WindowLogo = \_::$INFO->FullLogoPath;
$templ->SupTitle = \_::$INFO->Owner;
$templ->Title = \_::$INFO->Name;
$templ->Description = \_::$INFO->Slogan;
$templ->Draw();
?>
