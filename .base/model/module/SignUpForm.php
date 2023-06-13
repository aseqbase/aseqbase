<?php namespace MiMFa\Module;
class SignUpForm extends Module{
	public $Path = null;
	public $Image = null;
	public $Title = "Sign Up";
	public $SubmitLabel = "Register";
	public $SignatureLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-sign-in text-muted'></i>
		</span>";
	public $FirstNameLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-user text-muted'></i>
		</span>";
	public $LastNameLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-user text-muted'></i>
		</span>";
	public $EmailLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-envelope text-muted'></i>
		</span>";
	public $ContactLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-phone-square text-muted'></i>
		</span>";
	public $RouteLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-route text-muted'></i>
		</span>";
	public $PasswordLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-lock text-muted'></i>
		</span>";
	public $PasswordConfirmationLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-lock text-muted'></i>
		</span>";
	public $SignaturePlaceHolder = "UserName";
	public $FirstNamePlaceHolder = "First Name";
	public $LastNamePlaceHolder = "Last Name";
	public $EmailPlaceHolder = "Email Address";
	public $ContactPlaceHolder = "Phone Number";
	public $PasswordPlaceHolder = "Password";
	public $PasswordConfirmationPlaceHolder = "Confirm Password";
	public $RoutePlaceHolder = "Introduction Method";
	public $SignInLabel = "Do you have an account?";
	public $BackLabel = "Back to Home";
	public $WelcomeFormat = '<div class="welcome result success"><img class="welcome" src="%3$s"><p class="welcome">Hello %1$s,<br>You are signed in now, also there you can sign with another account!</p></div>';
	public $BackLink = "/";
	public $InternalMethod = "post";
	public $HasInternalMethod = true;
	public $HasExternalMethod = false;
	public $MultipleSignIn = false;
	public $SendActivationEmail = true;
	public $GroupID = null;
	/**
	 * Initial User Status:
     *		true/1:		Activated
     *		null/0:		Default Action
     *		false/-1:		Deactivated
	 * @var bool|null
	 */
	public $InitialStatus = null;

	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name ?> .input-group-prepend{
				margin-bottom: 0px;
			}

			.<?php echo $this->Name ?> .border-md {
				border-width: 2px;
			}
			
			.<?php echo $this->Name ?> .btn{
				color: #fff;
				border: none;
			}

			.<?php echo $this->Name ?> .btn-main {
				color: var(--BackColor-2);
				background-color: var(--ForeColor-2);
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

			.<?php echo $this->Name ?> div.welcome {
				text-align: center;
			}
			.<?php echo $this->Name ?> div.welcome img.welcome {
				width: 50%;
				max-width: 300px;
				border-radius: 100%;
			}
			.<?php echo $this->Name ?> div.welcome p.welcome {
				text-align: center;
			}
			
		</style>
		<?php
	}

	public function Echo(){
		$src = ($this->Path??\MiMFa\Library\User::$UpHandlerPath);
		$acc = \_::$INFO->User->Access(1);
		if($acc && !$this->MultipleSignIn) printf(__($this->WelcomeFormat), \_::$INFO->User->Name, \_::$INFO->User->Email, \_::$INFO->User->Image);
		else if(isValid($src)): ?>
			<div class="signform container">
				<div class="row align-items-center">
					<!-- For Demo Purpose -->
					<div class="col-md-5 pr-lg-5 mb-5 mb-md-0">
						<img src="<?php echo forceUrl($this->Image);?>" alt="" class="img-fluid mb-3 d-none d-md-block">
						<?php if($acc) printf(__($this->WelcomeFormat), \_::$INFO->User->Name, \_::$INFO->User->Email, \_::$INFO->User->Image); ?>
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
									
									<!-- First Name -->
									<div class="input-group col-lg-6 mb-4">
										<label for="firstName" class="input-group-prepend">
											<?php echo $this->FirstNameLabel; ?>
										</label>
										<input id="firstName" type="text" name="firstname" placeholder="<?php echo $this->FirstNamePlaceHolder; ?>" class="form-control bg-white border-left-0 border-md">
									</div>

									<!-- Last Name -->
									<div class="input-group col-lg-6 mb-4">
										<label for="lastName" class="input-group-prepend">
											<?php echo $this->LastNameLabel; ?>
										</label>
										<input id="lastName" type="text" name="lastname" placeholder="<?php echo $this->LastNamePlaceHolder; ?>" class="form-control bg-white border-left-0 border-md">
									</div>

									<!-- Email Address -->
									<div class="input-group col-lg-12 mb-4">
										<label for="email" class="input-group-prepend">
											<?php echo $this->EmailLabel; ?>
										</label>
										<input id="email" type="email" name="email" placeholder="<?php echo $this->EmailPlaceHolder; ?>" class="form-control bg-white border-left-0 border-md">
									</div>

									<!-- Phone Number -->
									<div class="input-group col-lg-12 mb-4">
										<label for="phoneNumber" class="input-group-prepend">
											<?php echo $this->ContactLabel; ?>
										</label>
										<select id="countryCode" name="countrycode" style="max-width: 80px" class="custom-select form-control bg-white border-left-0 border-md h-100 font-weight-bold text-muted">
											<?php
											for ($i = 0; $i < 100; $i++)
												echo "<option value='00$i'>+$i</option>";
                                            ?>
										</select>
										<input id="phoneNumber" type="tel" pattern="[1-9]\d{5,10}" name="phone" placeholder="<?php echo $this->ContactPlaceHolder; ?>" class="form-control bg-white border-md border-left-0 pl-3">
									</div>

									<!-- Route -->
									<div class="input-group col-lg-12 mb-4">
										<label for="route" class="input-group-prepend">
											<?php echo $this->RouteLabel;?>
										</label>
										<select id="route" name="route" class="form-control custom-select bg-white border-left-0 border-md">
											<option value="None"><?php echo __($this->RoutePlaceHolder); ?></option>
											<option value="Metting"><?php echo __("Metting"); ?></option>
											<option value="Card"><?php echo __("Visit Card"); ?></option>
											<option value="Advertisement"><?php echo __("Advertisement"); ?></option>
											<option value="Search"><?php echo __("Search Engine"); ?></option>
											<option value="Social"><?php echo __("Social Media"); ?></option>
											<option value="Other"><?php echo __("Other"); ?></option>
										</select>
									</div>
									
									<!-- User Name -->
									<div class="input-group col-lg-12 mb-4">
										<label for="username" class="input-group-prepend">
											<?php echo $this->SignatureLabel; ?>
										</label>
										<input id="username" type="text" name="username" autocomplete="username" placeholder="<?php echo $this->SignaturePlaceHolder; ?>" class="form-control bg-white border-left-0 border-md">
									</div>
									
									<!-- Password -->
									<div class="input-group col-lg-6 mb-4">
										<label for="password" class="input-group-prepend">
											<?php echo $this->PasswordLabel;?>
										</label>
										<input id="password" type="password" name="password" placeholder="<?php echo $this->PasswordPlaceHolder;?>" class="form-control bg-white border-left-0 border-md">
									</div>

									<!-- Password Confirmation -->
									<div class="input-group col-lg-6 mb-4">
										<label for="passwordConfirmation" class="input-group-prepend">
											<?php echo $this->PasswordConfirmationLabel;?>
										</label>
										<input id="passwordConfirmation" type="password" name="passwordConfirmation" placeholder="<?php echo $this->PasswordConfirmationPlaceHolder;?>" class="form-control bg-white border-left-0 border-md">
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
							<a class="text-muted font-weight-bold d-block" href="<?php echo \MiMFa\Library\User::$InHandlerPath; ?>"><?php echo __($this->SignInLabel);?></a>
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
                $(".<?php echo $this->Name ?> form").submit(function(e) {
					if ($(".<?php echo $this->Name ?> form .passwordConfirmation").value
						== $(".<?php echo $this->Name ?> form .password").value) return;
					e.preventDefault();
					$(".<?php echo $this->Name ?> form result").remove();
					$(".<?php echo $this->Name ?> form").append(Html.error("Confirmation of your password is not the same to the password!"));
                });
				handleForm(".<?php echo $this->Name ?> form",
					function (data, selector) {//success
						if (data.includes("result success")) {
							$('.<?php echo $this->Name ?> .signform').remove();
							$(".<?php echo $this->Name ?>").append(data);
							load();
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
			if(isValid($_req,"email") || isValid($_req,"password"))
			{
				$phone = getValid($_req,"countrycode").getValid($_req,"phone");
				if(strlen($phone) < 6) $phone = null;
				if(\_::$INFO->User->SignUp(
					getValid($_req,"username"),
					getValid($_req,"password"),
					getValid($_req,"email"),
					null,
					getValid($_req,"firstname"),
					getValid($_req,"lastname"),
					$phone,
					$this->GroupID,
					$this->InitialStatus
				))
				{
					echo "<div class='page'>";
                		echo "<div class='result success'>".__("Dear '".\_::$INFO->User->TemporaryName."', You registered successfully")."</div>";
						if($this->SendActivationEmail) PART("sign/active");
					echo "</div>";
				}
				else echo "<div class='result error'>".__("The user with these email and username could not register!")."</div>";
			}
			else echo "<div class='result warning'>".__("Please fill all required fields correctly!")."</div>";
		} catch(\Exception $ex) { echo "<div class='result error'>".__($ex->getMessage())."</div>"; }
		else echo "<div class='result warning'>".__("You are logged in!")."</div>";
    }
}
?>