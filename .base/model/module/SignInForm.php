<?php namespace MiMFa\Module;
class SignInForm extends Module{
	public $Path = null;
	public $Image = null;
	public $Title = "Sign In";
	public $SubmitLabel = "Sign In";
	public $SignatureLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-sign-in text-muted'></i>
		</span>";
	public $PasswordLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-lock text-muted'></i>
		</span>";
	public $SignaturePlaceHolder = "Email/Phone/UserName";
	public $PasswordPlaceHolder = "Password";
	public $SignUpLabel = "Do not have an account?";
	public $RememberLabel = "Forgot your password?";
	public $BackLabel = "Back to Home";
	public $BackLink = "/";
	public $InternalMethod = "post";
	public $HasInternalMethod = true;
	public $HasExternalMethod = false;
	public $SignUpIfNotRegistered = false;

	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name ?> .input-group-prepend{
				margin-bottom: 0px;
			}
			
			.<?php echo $this->Name ?> .btn{
				color: #fff;
				border: none;
			}

			.<?php echo $this->Name ?> .btn-main {
				color: var(--BackColor-2);
				background-color: var(--ForeColor-2);
			}

			.<?php echo $this->Name ?> .border-md {
				border-width: 2px;
			}
			.<?php echo $this->Name ?> .btn-facebook {
				background-color: #405D9D55 !important;
			}

			.<?php echo $this->Name ?> .btn-twitter {
				background-color: #42AEEC55 !important;
			}

			.<?php echo $this->Name ?> .btn-linkedin {
				background-color: #0e86a855 !important;
			}
		
			.<?php echo $this->Name ?> .btn-facebook:hover {
				background-color: #405D9D !important;
			}

			.<?php echo $this->Name ?> .btn-twitter:hover {
				background-color: #42AEEC !important;
			}

			.<?php echo $this->Name ?> .btn-linkedin:hover {
				background-color: #0e86a8 !important;
			}
			.<?php echo $this->Name ?> .form-control:not(select) {
				padding: 1.5rem 0.5rem;
			}
			.<?php echo $this->Name ?> select.form-control {
				height: 52px;
				padding-left: 0.5rem;
			}

			.<?php echo $this->Name ?> .form-control::placeholder {
				color: #ccc;
				font-weight: bold;
				font-size: 0.9rem;
			}
			.<?php echo $this->Name ?> .form-control:focus {
				box-shadow: none;
			}

		</style>
		<?php
	}

	public function Echo(){
		$src = ($this->Path??\MiMFa\Library\User::$InHandlerPath);
		if(isValid($src)): ?>
			<div class="signform container">
				<div class="row align-items-center">
					<!-- For Demo Purpose -->
					<div class="col-md-5 pr-lg-5 mb-5 mb-md-0">
						<img src="<?php echo forceUrl($this->Image);?>" alt="" class="img-fluid mb-3 d-none d-md-block">
						<h2><?php echo __($this->Title);?></h2>
						<p class="font-italic text-muted mb-0"><?php echo __($this->Description??\_::$INFO->Slogan) ?></p>
						<a href="<?php echo __($this->BackLink);?>" class="text-muted">
							<?php echo __($this->BackLabel);?>
						</a>
					</div>

					<!-- Login Form -->
					<div class="col-md col-lg ml-auto">
						<?php if($this->HasInternalMethod):?>
							<form action="<?php echo $src; ?>" method="<?php echo $this->InternalMethod;?>" class="signform">
								<div class="row">
									<!-- Email Address -->
									<div class="input-group col-lg-12 mb-4">
										<label for="username" class="input-group-prepend">
											<?php echo $this->SignatureLabel; ?>
										</label>
										<input id="username" type="text" name="username" autocomplete="username"
											   placeholder="<?php echo __($this->SignaturePlaceHolder);?>"
											   aria-label="<?php echo __($this->SignaturePlaceHolder);?>"
											   class="form-control bg-white border-left-0 border-md">
									</div>

									<!-- Password -->
									<div class="input-group col-lg-12 mb-4">
										<label for="password" class="input-group-prepend">
											<?php echo $this->PasswordLabel; ?>
										</label>
										<input id="password" type="password" name="password" placeholder="<?php echo __($this->PasswordPlaceHolder);?>" aria-label="<?php echo __($this->PasswordPlaceHolder);?>" class="form-control bg-white border-left-0 border-md">
									</div>

									<!-- Submit Button -->
									<div class="form-group col-lg-12 mx-auto mb-0">
										<button type="submit" id="submit" name="submit" class="btn btn-block btn-main py-2">
											<?php echo __($this->SubmitLabel);?>
										</button>
									</div>
								</div>
							</form>
						<?php endif;
						if($this->HasExternalMethod):?>
							<div class="signform">
								<!-- Divider Text -->
								<div class="form-group col-lg-12 mx-auto d-flex align-items-center my-4">
									<div class="border-bottom w-100 ml-5"></div>
									<span class="px-2 small text-muted font-weight-bold text-muted"><?php echo __("OR"); ?></span>
									<div class="border-bottom w-100 mr-5"></div>
								</div>

								<!-- Social Login -->
								<div class="form-group col-lg-12 mx-auto">
									<a href="#" class="btn btn-block py-2 btn-facebook">
										<i class="fa fa-facebook-f mr-2"></i>
										<span class="font-weight-bold"><?php echo __("Continue with Facebook");?></span>
									</a>
									<a href="#" class="btn btn-block py-2 btn-twitter">
										<i class="fa fa-twitter mr-2"></i>
										<span class="font-weight-bold"><?php echo __("Continue with Twitter");?></span>
									</a>
									<a href="#" class="btn btn-block py-2 btn-linkedin">
										<i class="fa fa-linkedin mr-2"></i>
										<span class="font-weight-bold"><?php echo __("Continue with LinkedIn");?></span>
									</a>
								</div>
							</div>
						<?php endif; ?>
						
						<div class="col-lg-12 mx-auto align-items-center my-4">
							<a class="text-muted font-weight-bold d-block" href="<?php echo \MiMFa\Library\User::$UpHandlerPath; ?>"><?php echo __($this->SignUpLabel);?></a>
							<a class="text-muted font-weight-bold d-block" href="<?php echo \MiMFa\Library\User::$RememberHandlerPath; ?>"><?php echo __($this->RememberLabel);?></a>
						</div>
					</div>
				</div>
			</div>
			<?php
		endif;
	}

	public function EchoScript(){
		parent::EchoScript();
		?>
		<script>
			$(function () {
				handleForm(".<?php echo $this->Name ?> form",
					function (data, selector)  {//success
						if (data.includes("result success")) {
							$('.<?php echo $this->Name ?> .signform').remove();
							$(".<?php echo $this->Name ?>").append(data);
						}
						else {
							$(".<?php echo $this->Name ?> form .result").remove();
							$(".<?php echo $this->Name ?> form").append(data);
						}
					}
				);
				$('.<?php echo $this->Name ?> :is(input, select)').on('focus', function () {
					$(this).parent().find('.<?php echo $this->Name ?> .input-group-text').css('border-color', 'var(--ForeColor-2)');
				});
				$('.<?php echo $this->Name ?> :is(input, select)').on('blur', function () {
					$(this).parent().find('.<?php echo $this->Name ?> .input-group-text').css('border-color', 'var(--ForeColor-2)');
				});
			});
		</script>
		<?php
    }

	public function Action(){
		$_req = $_REQUEST;
		switch(strtolower($this->InternalMethod)){
            case "get":
				$_req = $_GET;
			break;
            case "post":
				$_req = $_POST;
			break;
        }
        if(!ACCESS(1)) try {
			if(isValid($_req,"username") && isValid($_req,"password"))
                if($this->SignUpIfNotRegistered?
						\_::$INFO->User->SignInOrSignUp(getValid($_req,"username"), getValid($_req,"password")):
						\_::$INFO->User->SignIn(getValid($_req,"username"), getValid($_req,"password"))
				) {
                	echo "<div class='result success'>".__("Dear '".\_::$INFO->User->TemporaryName."', you have logged in successfully")."</div>";
                }
                else echo "<div class='result error'>".__("UserName or password is not correct!")."</div>";
			else echo "<div class='result warning'>".__("Please fill all fields correctly!")."</div>";
		} catch(\Exception $ex) { echo "<div class='result error'>".__($ex->getMessage())."</div>"; }
		else echo "<div class='result warning'>".__("You are logged in!")."</div>";
    }
}
?>