<?php if(!ACCESS("sign")) { ?>
	<style>
		.border-md {
			border-width: 2px;
		}
		.btn{
			color: #fff !important;
			border: none;
		}

		.btn-main {
			background-color: <?php echo \_::$TEMPLATE->ForeColor(2) ?> !important;
		}

		.btn-facebook {
			background-color: #405D9D55 !important;
		}

		.btn-twitter {
			background-color: #42AEEC55 !important;
		}

		.btn-linkedin {
			background-color: #0e86a855 !important;
		}
		
		.btn-facebook:hover {
			background-color: #405D9D !important;
		}

		.btn-twitter:hover {
			background-color: #42AEEC !important;
		}

		.btn-linkedin:hover {
			background-color: #0e86a8 !important;
		}
		.form-control:not(select) {
			padding: 1.5rem 0.5rem;
		}

		select.form-control {
			height: 52px;
			padding-left: 0.5rem;
		}

		.form-control::placeholder {
			color: #ccc;
			font-weight: bold;
			font-size: 0.9rem;
		}
		.form-control:focus {
			box-shadow: none;
		}

	</style>
	<script>
		$(function () {
			$('input, select').on('focus', function () {
				$(this).parent().find('.input-group-text').css('border-color', '<?php echo \_::$TEMPLATE->ForeColor(2) ?>');
			});
			$('input, select').on('blur', function () {
				$(this).parent().find('.input-group-text').css('border-color', '<?php echo \_::$TEMPLATE->ForeColor(2) ?>');
			});
		});
	</script>

	<div class="container">
		<div class="row align-items-center">
			<!-- For Demo Purpose -->
			<div class="col-md-5 pr-lg-5 mb-5 mb-md-0">
				<img src="/file/image/signin.svg" alt="" class="img-fluid mb-3 d-none d-md-block">
				<h2>Sign In</h2>
				<p class="font-italic text-muted mb-0"><?php echo \_::$INFO->Slogan ?></p>
				<a href="/?page=home&access=sign class="text-muted">
					Back to Home
				</a>
			</div>

			<!-- Login Form -->
			<div class="col-md-7 col-lg-6 ml-auto">
				<form action="#">
					<div class="row">

						<!-- Email Address -->
						<div class="input-group col-lg-12 mb-4">
							<div class="input-group-prepend">
								<span class="input-group-text bg-white px-4 border-md border-right-0">
									<i class="fa fa-envelope text-muted"></i>
								</span>
							</div>
							<input id="email" type="email" name="email" placeholder="Email Address" class="form-control bg-white border-left-0 border-md">
						</div>

						<!-- Password -->
						<div class="input-group col-lg-12 mb-4">
							<div class="input-group-prepend">
								<span class="input-group-text bg-white px-4 border-md border-right-0">
									<i class="fa fa-lock text-muted"></i>
								</span>
							</div>
							<input id="password" type="password" name="password" placeholder="Password" class="form-control bg-white border-left-0 border-md">
						</div>

						<!-- Submit Button -->
						<div class="form-group col-lg-12 mx-auto mb-0">
							<a href="/?page=home&access=main" class="btn btn-block btn-main py-2">
								<span class="font-weight-bold">Login</span>
							</a>
						</div>

						<!-- Divider Text -->
						<div class="form-group col-lg-12 mx-auto d-flex align-items-center my-4">
							<div class="border-bottom w-100 ml-5"></div>
							<span class="px-2 small text-muted font-weight-bold text-muted">OR</span>
							<div class="border-bottom w-100 mr-5"></div>
						</div>

						<!-- Social Login -->
						<div class="form-group col-lg-12 mx-auto">
							<a href="#" class="btn btn-block py-2 btn-facebook">
								<i class="fa fa-facebook-f mr-2"></i>
								<span class="font-weight-bold">Continue with Facebook</span>
							</a>
							<a href="#" class="btn btn-block py-2 btn-twitter">
								<i class="fa fa-twitter mr-2"></i>
								<span class="font-weight-bold">Continue with Twitter</span>
							</a>
							<a href="#" class="btn btn-block py-2 btn-linkedin">
								<i class="fa fa-linkedin mr-2"></i>
								<span class="font-weight-bold">Continue with LinkedIn</span>
							</a>
						</div>

						<!-- Already Registered -->
						<div class="col-lg-12 mx-auto d-flex align-items-center  my-4">
							<p class="text-muted font-weight-bold">You do not have an account? <a href="/?page=sign-up" class="text-primary ml-2">Sign Up</a></p>
						</div>

					</div>
				</form>
			</div>
		</div>
	</div>
<?php } else PART("sign-up"); ?>