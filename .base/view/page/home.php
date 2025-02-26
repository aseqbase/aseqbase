<style>
	.page-home {
		padding: 10px 10px 50px;
	}
</style>

<div class="page-home">
	<?php part("small-header"); ?>
	<!--<?php //if(inspect("sign")){ ?>
		<div class="row sign">
			<div class="col-sm" data-aos="zoom-out" data-aos-duration="600">
				<a class="btn" href="<?php echo GETUrl('sign-in') ?>">Sign In</a>
				<a class="btn" href="<?php echo GETUrl('sign-up') ?>">Sign Up</a>
			</div>
		</div>
	<?php //} ?>-->
<?php module("RingSlide");
$module = new \MiMFa\Module\RingSlide();
$module->Image = \_::$Info->LogoPath;
$module->Items = \_::$Info->Services;
$module->Render();
?>
</div>