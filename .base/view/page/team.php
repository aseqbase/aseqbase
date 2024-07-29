<div class="page page-team">
	<?php
	MODULE("PrePage");
	$module = new \MiMFa\Module\PrePage();
	$module->Title = "Team";
	$module->Image = \MiMFa\Library\Local::GetUrl("/asset/symbol/team.png");
	$module->Description = \_::$INFO->OwnerDescription;
	$module->Draw();
	MODULE("Members");
	$module = new \MiMFa\Module\Members();
	$module->Items = \_::$INFO->Members;
	$module->Draw();
	?>
</div>