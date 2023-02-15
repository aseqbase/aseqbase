<style>
	.page-home {
		padding: 10px 10px 50px;
	}
</style>

<div class="page-home">
	<?php PART("small-header"); ?>
	<!--<?php //if(ACCESS("sign")){ ?>
		<div class="row sign">
			<div class="col-sm" data-aos="zoom-out" data-aos-duration="600">
				<a class="btn" href="<?php echo GETURL('sign-in') ?>">Sign In</a>
				<a class="btn" href="<?php echo GETURL('sign-up') ?>">Sign Up</a>
			</div>
		</div>
	<?php //} ?>-->
<?php MODULE("RingSlide");
$module = new \MiMFa\Module\RingSlide();
$module->Image = \_::$INFO->FullLogoPath;
$module->Items = \_::$INFO->Services;
$module->Draw();
?>
</div>
