<?php MODULE("FixedBanner");
	$module = new \MiMFa\Module\FixedBanner();
	$module->Title = \_::$INFO->FullName;
	$module->Description = \_::$INFO->Slogan;
	$module->Logo = forceUrl(\_::$INFO->FullLogoPath);
	$module->Image = \_::$TEMPLATE->Overlay(1);
	$module->Items = \_::$INFO->Services;
	$module->Type = "box";
	$module->Content = "<center><a class='btn btn-primary' href='".\_::$INFO->DownloadPath."'>".
		__("Download",true,false)
	."</a></center>";
	$module->Draw();
?>
