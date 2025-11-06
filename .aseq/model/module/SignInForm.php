<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Convert;

module("Form");
class SignInForm extends Form{
	public $Action = null;
	public $Title = "Sign In";
	public $Image = "sign-in";
	public $SubmitLabel = "Sign In";
	public $SignatureLabel = "<i class='icon fa fa-sign-in'></i>";
	public $SignatureValue = null;
	public $PasswordLabel = "<i class='icon fa fa-lock'></i>";
	public $PasswordValue = null;
	public $SignaturePlaceHolder = "Email/Phone/UserName";
	public $PasswordPlaceHolder = "Password";
	public $SignUpLabel = "Do not have an account?";
	public $SignUpPath = null;
	public $RecoverLabel = "Forgot your password?";
	public $RecoverPath = null;
	public $SignedInWarning = "You are logged in!";
	public $IncompleteWarning = "Please fill all fields correctly!";
	public $IncorrectWarning = "The 'UserName' or 'Password' is incorrect!";
	public $CorrectConfirmingFormat = 'Dear \'$NAME\', you have logged in successfully';
	public $WelcomeFormat = null;//'<div class="welcome result success"><br><p class="welcome">Hello dear "$NAME",<br>You are signed in now!</p></div>';
	public $Welcome = null;
	public $AllowInternalMethod = true;
	public $AllowExternalMethod = false;
	public $MultipleSignIn = false;
	/**
	 * Simple signing up if the user is not exists
	 * @var 
	 */
	public $SignUp = false;
	public $BlockTimeout = 5000;
	public $ResponseView = null;
    public $Printable = false;
	
	public function __construct(){
        parent::__construct();
		$this->Action = \_::$User->InHandlerPath;
		$this->SuccessPath = \_::$Address->Path;
		$this->Welcome = function(){ return part(\_::$User->DashboardHandlerPath, print:false); };
	}

	public function GetStyle(){
		if($this->AllowDecoration) return ((\_::$User->GetAccess(\_::$User->UserAccess) && !$this->MultipleSignIn)?"":parent::GetStyle()).Html::Style("
			.{$this->Name} .btn.facebook {
				background-color: #405D9D55 !important;
			}

			.{$this->Name} .btn.twitter {
				background-color: #42AEEC55 !important;
			}

			.{$this->Name} .btn.linkedin {
				background-color: #0e86a855 !important;
			}

			.{$this->Name} .btn.facebook:hover {
				background-color: #405D9D !important;
			}

			.{$this->Name} .btn.twitter:hover {
				background-color: #42AEEC !important;
			}

			.{$this->Name} .btn.linkedin:hover {
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
		if(\_::$User->GetAccess(\_::$User->UserAccess) && !$this->MultipleSignIn)
			return $this->GetHeader().Convert::ToString($this->Welcome);
        else return parent::Get();
	}
	public function GetHeader(){
        if(\_::$User->GetAccess(\_::$User->UserAccess) && !isEmpty($this->WelcomeFormat))
			return __(Convert::FromDynamicString($this->WelcomeFormat));
    }
	public function GetFields(){
        if($this->AllowInternalMethod){
			yield $this->SignatureValue?
				Html::HiddenInput("Signature", $this->SignatureValue):
				Html::LargeSlot(
					Html::Label($this->SignatureLabel, "Signature" , ["class"=>"prepend"]).
					Html::TextInput("Signature", $this->SignatureValue, ["placeholder"=> $this->SignaturePlaceHolder, "autocomplete"=>"username"])
				, ["class"=>"field col"]);
			yield $this->PasswordValue?
				Html::HiddenInput("Password", $this->PasswordValue):Html::LargeSlot(
					Html::Label($this->PasswordLabel, "Password" , ["class"=>"prepend"]).
					Html::SecretInput("Password", $this->PasswordValue, ["placeholder"=> $this->PasswordPlaceHolder, "autocomplete"=>"password"])
				, ["class"=>"field col"]);
		}
		yield from parent::GetFields();
    }
	public function GetFooter(){
        return parent::GetFooter()
			.Html::LargeSlot(
				Html::Link($this->SignUpLabel, $this->SignUpPath??\_::$User->UpHandlerPath)
			, ["class"=>"col-lg-12"])
			.Html::LargeSlot(
				Html::Link($this->RecoverLabel, $this->RecoverPath??\_::$User->RecoverHandlerPath)
			, ["class"=>"col-lg-12"]);
    }

	public function Post(){
		if(!\_::$User->GetAccess(\_::$User->UserAccess) || $this->MultipleSignIn) try {
			$received = receivePost();
			$signature = get($received,"Signature" );
			$password = get($received,"Password" );
			if(isValid($signature) && isValid($password)) {
				$res = $this->SignUp && isEmail($signature)?
						\_::$User->SignInOrSignUp($signature, $password, $signature):
						\_::$User->SignIn($signature, $password);
				if($res === true)
                	return $this->GetSuccess(Convert::FromDynamicString($this->CorrectConfirmingFormat));
				elseif($res === false)
					return $this->GetError($this->IncorrectWarning);
				elseif(is_null($res))
					return deliverBreaker($this->GetError("This account is not active yet!"), null, \_::$User->ActiveHandlerPath . "?signature=$signature".(\_::$Address->Query?"&".\_::$Address->Query:""));
				else
					return $this->GetError($res);
			}
			else return $this->GetWarning($this->IncompleteWarning);
		} catch(\Exception $ex) { return $this->GetError($ex); }
		$this->Result = true;
		return $this->GetMessage($this->SignedInWarning);
    }
	
	public function Delete(){
		if(\_::$User->GetAccess(\_::$User->UserAccess)) try {
			$user = \_::$User->Get();
			if (!isValid($user)) return $this->GetSuccess("You are no longer signed in!");
			elseif(\_::$User->SignOut()) return $this->GetSuccess("You signed out successfully!");
			else return $this->GetError("Something went wrong in signing out!");
		} catch(\Exception $ex) { return $this->GetError($ex); }
		$this->Result = true;
		return $this->GetMessage("You are not signed in!");
    }
}
?>