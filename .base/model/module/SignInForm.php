<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
use MiMFa\Library\Convert;
use MiMFa\Library\User;
MODULE("Form");
class SignInForm extends Form{
	public $Action = null;
	public $Title = "Sign In";
	public $Image = "sign-in";
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
	public $WelcomeFormat = '<div class="welcome result success"><img class="welcome" src="$IMAGE"><br><p class="welcome">Hello dear $NAME,<br>You are signed in now!</p></div>';
	public $WelcomePart = null;
	public $HasInternalMethod = true;
	public $HasExternalMethod = false;
	public $MultipleSignIn = false;
	public $SignUpIfNotRegistered = false;

	public function __construct(){
        parent::__construct();
		$this->Action = User::$InHandlerPath;
		$this->WelcomePart = User::$DashboardHandlerPath;
		$this->SuccessPath = \_::$PATH;
	}

	public function EchoStyle(){
		parent::EchoStyle();?>
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
				margin: var(--Size-1);
			}
			.<?php echo $this->Name ?> div.welcome p.welcome {
				text-align: center;
			}
			
		</style>
		<?php
	}

	public function Echo(){
		if(getAccess(\_::$CONFIG->UserAccess) && !$this->MultipleSignIn){
			$this->EchoHeader();
			PART($this->WelcomePart);
        } else parent::Echo();
	}
	public function EchoHeader(){
        if(getAccess(\_::$CONFIG->UserAccess))
			echo __(Convert::FromDynamicString($this->WelcomeFormat));
    }
	public function EchoFields(){
        if($this->HasInternalMethod):?>
			<div class="row">
				<!-- Signature Address -->
				<div class="input-group col-lg-12 mb-4">
                    <label for="Signature" class="input-group-prepend">
                        <?php echo $this->SignatureLabel; ?>
                    </label>
                    <input id="Signature" name="Signature" type="text" autocomplete="username"
                        placeholder="<?php echo __($this->SignaturePlaceHolder);?>"
                        aria-label="<?php echo __($this->SignaturePlaceHolder);?>"
                        class="form-control bg-white border-left-0 border-md" />
				</div>
			</div>
			<div class="row">
				<!-- Password -->
				<div class="input-group col-lg-12 mb-4">
					<label for="Password" class="input-group-prepend">
						<?php echo $this->PasswordLabel; ?>
					</label>
                    <input id="Password" name="Password" type="password" placeholder="<?php echo __($this->PasswordPlaceHolder);?>" aria-label="<?php echo __($this->PasswordPlaceHolder);?>" class="form-control bg-white border-left-0 border-md" />
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
			<a class="text-muted font-weight-bold d-block" href="<?php echo User::$UpHandlerPath; ?>"><?php echo __($this->SignUpLabel);?></a>
			<a class="text-muted font-weight-bold d-block" href="<?php echo User::$RecoverHandlerPath; ?>"><?php echo __($this->RememberLabel);?></a>
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
		if(!getAccess(\_::$CONFIG->UserAccess)) try {
			if(isValid($_req,"Signature") && isValid($_req,"Password")){
				$res = $this->SignUpIfNotRegistered?
						\_::$INFO->User->SignInOrSignUp(getValid($_req,"Signature"), getValid($_req,"Password")):
						\_::$INFO->User->SignIn(getValid($_req,"Signature"), getValid($_req,"Password"));
				if($res === true)
                	echo HTML::Success("Dear '".\_::$INFO->User->TemporaryName."', you have logged in successfully");
				elseif($res === false)
					echo HTML::Error("UserName or Password is not correct!");
				else
					echo HTML::Error($res);
			}
			else echo HTML::Warning("Please fill all fields correctly!");
		} catch(\Exception $ex) { echo HTML::Error($ex); }
		else echo HTML::Warning("You are logged in!");
    }
}
?>