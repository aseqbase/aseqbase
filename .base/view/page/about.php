<div class="page page-about">
	<?php
		MODULE("PrePage");
		$module = new MiMFa\Module\PrePage();
		$module->Title = "About";
		$module->Draw();
    ?>
	<div class="container content">
		<div class="row">
			<div class="col-md">
				<h2 class="align-center">
					<strong>
						<?php echo __(\_::$INFO->FullName); ?>
					</strong>
				</h2>
                <?php
                MODULE("Image");
                $module = new MiMFa\Module\Image();
                $module->Image = \_::$INFO->FullLogoPath;
                $module->Class = "align-center";
                $module->Height = "15vmax";
                $module->Draw();
                ?>
				<h4 class="align-center">
					<?php echo __(\_::$INFO->FullSlogan); ?>
				</h4>
				<p class="align-center">
					<?php echo __(\_::$INFO->Description); ?>
				</p>
			</div>
		</div>
		<div class='row'>
            <?php
			$i = 0;
			if(isValid(\_::$INFO->OwnerDescription))
                foreach(MiMFa\Library\Convert::ToParagraphs(\_::$INFO->OwnerDescription) as $item) {
                    if($i++%2 === 0) echo "</div><div class='row'>";
                    echo "<div class='col-md text'>".__($item)."</div>";
                }
			$i = 0;
			if(isValid(\_::$INFO->FullDescription))
                foreach(MiMFa\Library\Convert::ToParagraphs(\_::$INFO->FullDescription) as $item) {
                    if($i++%2 === 0) echo "</div><div class='row'>";
                    echo "<div class='col-md text'>".__($item)."</div>";
                }
            ?>
		</div>
		<div class="row">
			<div class="col-md">
				<h4><?php echo __(\_::$INFO->Slogan); ?></h4>
			</div>
		</div>
	</div>
</div>