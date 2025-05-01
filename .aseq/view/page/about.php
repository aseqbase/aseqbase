<div class="page page-about">
	<?php
	module("PrePage");
	$module = new MiMFa\Module\PrePage();
	$module->Title = \_::$Info->FullName??"About Us";
	swap($module, $data);
	?>
	<div class="container content">
		<div class="row">
			<div class="col-md">
				<?php
				$module->Render();
				module("Image");
				$module = new MiMFa\Module\Image();
				$module->Image = \_::$Info->FullLogoPath;
				$module->Class = "align-center";
				$module->Height = "15vmax";
				$module->Render();
				?>
				<h4 class="align-center">
					<?php echo __(\_::$Info->FullSlogan); ?>
				</h4>
				<p class="align-center">
					<?php echo __(\_::$Info->Description); ?>
				</p>
			</div>
		</div>
		<div class='row'>
			<?php
			$i = 0;
			if (isValid(\_::$Info->OwnerDescription))
				foreach (MiMFa\Library\Convert::ToParagraphs(\_::$Info->OwnerDescription) as $item) {
					if ($i++ % 2 === 0)
						echo "</div><div class='row'>";
					echo "<div class='col-md text'>" . __($item) . "</div>";
				}
			$i = 0;
			if (isValid(\_::$Info->FullDescription))
				foreach (MiMFa\Library\Convert::ToParagraphs(\_::$Info->FullDescription) as $item) {
					if ($i++ % 2 === 0)
						echo "</div><div class='row'>";
					echo "<div class='col-md text'>" . __($item) . "</div>";
				}
			?>
		</div>
		<div class="row">
			<div class="col-md">
				<h4><?php echo __(\_::$Info->Slogan); ?></h4>
			</div>
		</div>
	</div>
</div>