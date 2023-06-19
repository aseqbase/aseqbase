<?php
namespace MiMFa\Module;
MODULE("Form");
class SignInForm extends Form{
	public $Action = null;
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
	public $WelcomeFormat = '<div class="welcome result success"><img class="welcome" src="%3$s"><p class="welcome">Hello %1$s,<br>You are signed in now, also there you can sign with another account!</p></div>';
	public $HasInternalMethod = true;
	public $HasExternalMethod = false;
	public $MultipleSignIn = false;
	public $SignUpIfNotRegistered = false;
	
	public function __construct(){
        parent::__construct();
		$this->Action = \MiMFa\Library\User::$InHandlerPath;
	}

	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
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
		if(\_::$INFO->User->Access(1) && !$this->MultipleSignIn)
			printf(__($this->WelcomeFormat), \_::$INFO->User->Name, \_::$INFO->User->Email, \_::$INFO->User->Image);
		else parent::Echo();
	}
	public function EchoHeader(){
        if(\_::$INFO->User->Access(1))
			printf(__($this->WelcomeFormat), \_::$INFO->User->Name, \_::$INFO->User->Email, \_::$INFO->User->Image);
    }
	public function EchoFields(){
        if($this->HasInternalMethod):?>
			<div class="row">
				<!-- Signature Address -->
				<div class="input-group col-lg-12 mb-4">
					<label for="username" class="input-group-prepend">
						<?php echo $this->SignatureLabel; ?>
					</label>
					<input id="username" type="text" name="username" autocomplete="username"
							placeholder="<?php echo __($this->SignaturePlaceHolder);?>"
							aria-label="<?php echo __($this->SignaturePlaceHolder);?>"
							class="form-control bg-white border-left-0 border-md">
				</div>
			</div>
			<div class="row">
				<!-- Password -->
				<div class="input-group col-lg-12 mb-4">
					<label for="password" class="input-group-prepend">
						<?php echo $this->PasswordLabel; ?>
					</label>
					<input id="password" type="password" name="password" placeholder="<?php echo __($this->PasswordPlaceHolder);?>" aria-label="<?php echo __($this->PasswordPlaceHolder);?>" class="form-control bg-white border-left-0 border-md">
				</div>
			</div>
		<?php endif;
		if($this->HasExternalMethod):?>
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
		<?php endif;
    }
	public function EchoFooter(){
        ?>
		<div class="col-lg-12 mx-auto align-items-center my-4">
			<a class="text-muted font-weight-bold d-block" href="<?php echo \MiMFa\Library\User::$UpHandlerPath; ?>"><?php echo __($this->SignUpLabel);?></a>
			<a class="text-muted font-weight-bold d-block" href="<?php echo \MiMFa\Library\User::$RememberHandlerPath; ?>"><?php echo __($this->RememberLabel);?></a>
		</div>
		<?php
    }

	public function Action(){
		$_req = $_REQUEST;
		switch(strtolower($this->Method)){
            case "get":
				$_req = $_GET;
			break;
            case "post":
				$_req = $_POST;
			break;
        }
		if(!ACCESS(1)) try {
			if(isValid($_req,"username") && isValid($_req,"password")){
				$res = $this->SignUpIfNotRegistered?
						\_::$INFO->User->SignInOrSignUp(getValid($_req,"username"), getValid($_req,"password")):
						\_::$INFO->User->SignIn(getValid($_req,"username"), getValid($_req,"password"));
				if($res === true)
                	echo "<div class='page result success'>".__("Dear '".\_::$INFO->User->TemporaryName."', you have logged in successfully")."</div>";
				elseif($res === false)
					echo "<div class='result error'>".__("UserName or password is not correct!")."</div>";
				else
					echo "<div class='result error'>".__($res)."</div>";
			}
			else echo "<div class='result warning'>".__("Please fill all fields correctly!")."</div>";
		} catch(\Exception $ex) { echo "<div class='result error'>".__($ex->getMessage())."</div>"; }
		else echo "<div class='result warning'>".__("You are logged in!")."</div>";
    }
}
?>