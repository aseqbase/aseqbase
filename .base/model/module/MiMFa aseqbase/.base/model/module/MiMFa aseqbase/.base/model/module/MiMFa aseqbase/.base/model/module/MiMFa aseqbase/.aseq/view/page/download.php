<script>load('<?php echo \_::$INFO->DownloadPath??\_::$INFO->PortableDownloadPath; ?>'); </script>
<?php
	MODULE("PrePage");
	$module = new MiMFa\Module\PrePage();
	$module->Title = "Download";
	$module->Draw();
	MODULE("FixedScreen");
	$module = new MiMFa\Module\FixedScreen();
	$module->Class = "horizontal-center";
	$module->Content = "
	<div class='vertical-middle'>
		<a class='btn btn-primary' href='".\_::$INFO->DownloadPath."'>".__("If you do not redirected automatically, Click on this button to Download")."</a>
	</div>";
	$module->Draw();
?>