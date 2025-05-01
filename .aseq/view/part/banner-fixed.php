<?php module("FixedBanner");
$module = new \MiMFa\Module\FixedBanner();
$module->Title = \_::$Info->FullName;
$module->Description = \_::$Info->Slogan;
$module->Logo = \_::$Info->FullLogoPath;
$module->Image = \_::$Front->Overlay(1);
$module->Items = \_::$Info->Services;
$module->Type = "box";
if (isValid(\_::$Info, "DownloadPath"))
	$module->Content = \MiMFa\Library\Html::Center(
		\MiMFa\Library\Html::Link("Download", \_::$Info->DownloadPath, ['class' => 'btn outline'])
	);
swap($module, $data);
$module->Render();
?>