<div class="page page-team">
	<?php
	module("PrePage");
	$module = new \MiMFa\Module\PrePage();
	$module->Title = "Team";
	$module->Image = "users";
	$module->Description = \_::$Info->OwnerDescription;
	$module->Render();
	module("Members");
	$module = new \MiMFa\Module\Members();
	$module->Items = \_::$Info->Members;
    dip($module, $data);
	$module->Render();
	?>
</div>