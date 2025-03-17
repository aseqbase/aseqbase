<div class="page page-team">
	<?php
	module("PrePage");
	$module = new \MiMFa\Module\PrePage();
	$module->Title = "Team";
	$module->Image = \MiMFa\Library\Local::GetUrl("group");
	$module->Description = \_::$Info->OwnerDescription;
	$module->Render();
	module("Members");
	$module = new \MiMFa\Module\Members();
	$module->Items = \_::$Info->Members;
    swap($module, $data);
	$module->Render();
	?>
</div>