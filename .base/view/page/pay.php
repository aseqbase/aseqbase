<div class="page page-pay">
    <?php
	MODULE("PrePage");
	$module = new MiMFa\Module\PrePage();
	$module->Title = "Payment";
	$module->Draw();
	PART("pay");
    ?>
</div>