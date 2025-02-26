
<?php module("FixedBanner");
	$module = new \MiMFa\Module\FixedBanner();
	$module->Title = \_::$Info->FullName;
	$module->Description = \_::$Info->Slogan;
	$module->Logo = forceUrl(\_::$Info->FullLogoPath);
	$module->Image = \_::$Front->Overlay(1);
	$module->Items = \_::$Info->Services;
	$module->Type = "box";
	if(isValid(\_::$Info,"DownloadPath"))
		$module->Content = "<center><a class='btn btn-outline' href='".\_::$Info->DownloadPath."'>".
			__("Download",true,false)
		."</a></center>";
	$module->Render();
?>
