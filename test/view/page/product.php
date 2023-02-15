<?php
	MODULE("PrePage");
	$module= new MiMFa\Module\PrePage();
	$module->Title = "Product";
	$module->Draw();
?>
<center><img src="<?php echo $product; ?>" style="max-Height:100vh;max-width:100vw;"></center>