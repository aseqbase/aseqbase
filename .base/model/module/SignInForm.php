<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Convert;
use MiMFa\Library\User;
module("Form");
class SignInForm extends Form{
	public $Action = null;
	public $Title = "Sign In";
	public $Image = "sign-in";
	public $SubmitLabel = "Sign In";
	public $SignatureLabel = "<i class='fa fa-sign-in'></i>";
	public $PasswordLabel = "<i class='fa fa-lock'></i>";
	public $SignaturePlaceHolder = "Email/Phone/UserName";
	public $PasswordPlaceHolder = "Password";
	public $SignUpLabel = "Do not have an account?";
	public $RememberLabel = "Forgot your password?";
	public $LoggedInWarning = "You are logged in!";
	public $IncompleteWarning = "Please fill all fields correctly!";
	public $IncorrectWarning = "UserName or Password is not correct!";
	public $CorrectConfirmingFormat = 'Dear \'$NAME\', you have logged in successfully';
	public $WelcomeFormat = null;//'<div class="welcome result success"><br><p class="welcome">Hello dear "$NAME",<br>You are signed in now!</p></div>';
	public $Welcome = null;
	public $HasInternalMethod = true;
	public $HasExternalMethod = false;
	public $MultipleSignIn = false;
	/**
	 * Simple signing up if the user is not exists
	 * @var 
	 */
	public $SignUp = false;
	public $BlockTimeout = 5000;
	public $ResponseView = null;

	public function __construct(){
        parent::__construct();
		$this->Action = User::$InHandlerPath;
		$this->SuccessPath = \Req::$Path;
		$this->Welcome = function(){ return part(User::$DashboardHandlerPath, print:false); };
	}

	public function GetStyle(){
		if($this->HasDecoration) return ((auth(\_::$Config->UserAccess) && !$this->MultipleSignIn)?"":parent::GetStyle()).Html::Style("
			.{$this->Name} .btn-facebook {
				background-color: #405D9D55 !important;
			}

			.{$this->Name} .btn-twitter {
				background-color: #42AEEC55 !important;
			}

			.{$this->Name} .btn-linkedin {
				background-color: #0e86a855 !important;
			}

			.{$this->Name} .btn-facebook:hover {
				background-color: #405D9D !important;
			}

			.{$this->Name} .btn-twitter:hover {
				background-color: #42AEEC !important;
			}

			.{$this->Name} .btn-linkedin:hover {
				background-color: #0e86a8 !important;
			}
			.{$this->Name} div.welcome {
				text-align: center;
			}
			.{$this->Name} div.welcome p.welcome {
				text-align: center;
			}
		");
		else return parent::GetStyle();
	}

	public function Get(){
		if(auth(\_::$Config->UserAccess) && !$this->MultipleSignIn)
			return $this->GetHeader().Convert::ToString($this->Welcome);
        else return parent::Get();
	}
	public function GetHeader(){
        if(auth(\_::$Config->UserAccess) && !isEmpty($this->WelcomeFormat))
			return __(Convert::FromDynamicString($this->WelcomeFormat));
    }
	public function GetFields(){
        if($this->HasInternalMethod){
			yield Html::LargeSlot(
					Html::Label($this->SignatureLabel, "Signature" , ["class"=>"prepend"]).
					Html::ValueInput("Signature" , ["placeholder"=> $this->SignaturePlaceHolder, "autocomplete"=>"username"])
				, ["class"=>"field col"]);
			yield Html::LargeSlot(
					Html::Label($this->PasswordLabel, "Password" , ["class"=>"prepend"]).
					Html::SecretInput("Password" , ["placeholder"=> $this->PasswordPlaceHolder, "autocomplete"=>"Password"])
				, ["class"=>"field col"]);
		}
		yield from parent::GetFields();
    }
	public function GetFooter(){
        return parent::GetFooter()
			.Html::LargeSlot(
				Html::Link($this->SignUpLabel, User::$UpHandlerPath)
			, ["class"=>"col-lg-12"])
			.Html::LargeSlot(
				Html::Link($this->RememberLabel, User::$RecoverHandlerPath)
			, ["class"=>"col-lg-12"]);
    }

	public function Post(){
		if(!auth(\_::$Config->UserAccess) || $this->MultipleSignIn) try {
			$received = \Req::Post();
			$signature = get($received,"Signature" );
			$password = get($received,"Password" );
			if(isValid($signature) && isValid($password)) {
				$res = $this->SignUp && isEmail($signature)?
						\_::$Back->User->SignInOrSignUp($signature, $password, $signature):
						\_::$Back->User->SignIn($signature, $password);
				if($res === true)
                	return $this->GetSuccess(Convert::FromDynamicString($this->CorrectConfirmingFormat));
				elseif($res === false)
					return $this->GetError($this->IncorrectWarning);
				else
					return $this->GetError($res);
			}
			else return $this->GetWarning($this->IncompleteWarning);
		} catch(\Exception $ex) { return $this->GetError($ex); }
		$this->Result = true;
		return $this->GetMessage($this->LoggedInWarning);
    }
	
	public function Delete(){
		if(auth(\_::$Config->UserAccess)) try {
			$user = \_::$Back->User->Get();
			if (!isValid($user)) return $this->GetSuccess("You are no longer signed in!");
			elseif(\_::$Back->User->SignOut()) return $this->GetSuccess("You signed out successfully!");
			else return $this->GetError("There a problem is occured in signing out!");
		} catch(\Exception $ex) { return $this->GetError($ex); }
		$this->Result = true;
		return $this->GetMessage("You are not signed in!");
    }
}
?>