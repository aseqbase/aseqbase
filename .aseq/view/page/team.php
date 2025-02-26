<div class="page page-team">
	<?php
	module("PrePage");
	$module = new \MiMFa\Module\PrePage();
	$module->Title = "Team";
	$module->Image = \MiMFa\Library\Local::GetUrl("/asset/symbol/team.png");
	$module->Description = \_::$Info->OwnerDescription;
	$module->Render();
	module("Members");
	$module = new \MiMFa\Module\Members();
	$module->Items = \_::$Info->Members;
	$module->Render();
	?>
</div>