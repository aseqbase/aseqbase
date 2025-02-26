<div class="page page-download">
<script>load('<?php echo \_::$Info->DownloadPath; ?>'); </script>
<?php
	module("PrePage");
	$module = new MiMFa\Module\PrePage();
	$module->Title = "Download";
	$module->Render();
	module("FixedScreen");
	$module = new MiMFa\Module\FixedScreen();
	$module->Class = "horizontal-center";
	$module->Content = "
	<div class='vertical-middle'>
		<a class='btn btn-primary' href='".\_::$Info->DownloadPath."'>".__("If you do not redirected automatically, Click on this button to Download")."</a>
	</div>";
	$module->Render();
?>
</div>