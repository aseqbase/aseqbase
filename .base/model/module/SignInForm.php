<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
use MiMFa\Library\Convert;
use MiMFa\Library\User;
MODULE("Form");
class SignInForm extends Form{
	public $Capturable = true;
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
	public $WelcomeFormat = null;//'<div class="welcome result success"><br><p class="welcome">Hello dear "$NAME",<br>You are signed in now!</p></div>';
	public $Welcome = null;
	public $HasInternalMethod = true;
	public $HasExternalMethod = false;
	public $MultipleSignIn = false;
	public $SignUpIfNotRegistered = false;

	public function __construct(){
        parent::__construct();
		$this->Action = User::$InHandlerPath;
		$this->Welcome = function(){ return PART(User::$DashboardHandlerPath, print:false); };
		$this->SuccessPath = \_::$PATH;
	}

	public function GetStyle(){
		if($this->HasDecoration) return ((getAccess(\_::$CONFIG->UserAccess) && !$this->MultipleSignIn)?"":parent::GetStyle()).HTML::Style("
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
		if(getAccess(\_::$CONFIG->UserAccess) && !$this->MultipleSignIn){
			return $this->GetHeader().Convert::ToString($this->Welcome);
        } else return parent::Get();
	}
	public function GetHeader(){
        if(getAccess(\_::$CONFIG->UserAccess) && !isEmpty($this->WelcomeFormat))
			return __(Convert::FromDynamicString($this->WelcomeFormat));
    }
	public function GetFields(){
        if($this->HasInternalMethod){
			yield HTML::Rack(
				HTML::LargeSlot(
					HTML::Label($this->SignatureLabel, "Signature", ["class"=>"prepend"]).
					HTML::ValueInput("Signature", ["placeholder"=> $this->SignaturePlaceHolder])
				, ["class"=>"field col", "autocomplete"=>"username"])
			);
			yield HTML::Rack(
				HTML::LargeSlot(
					HTML::Label($this->PasswordLabel, "Password", ["class"=>"prepend"]).
					HTML::SecretInput("Password", ["placeholder"=> $this->PasswordPlaceHolder])
				, ["class"=>"field col", "autocomplete"=>"password"])
			);
		}
		yield from parent::GetFields();
    }
	public function GetFooter(){
        return parent::GetFooter()
			.HTML::LargeSlot(
				HTML::Link($this->SignUpLabel, User::$UpHandlerPath)
			, ["class"=>"col-lg-12"])
			.HTML::LargeSlot(
				HTML::Link($this->RememberLabel, User::$RecoverHandlerPath)
			, ["class"=>"col-lg-12"]);
    }

	public function GetAction(){
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
                	return HTML::Success("Dear '".\_::$INFO->User->TemporaryName."', you have logged in successfully");
				elseif($res === false)
					return HTML::Error("UserName or Password is not correct!");
				else
					return HTML::Error($res);
			}
			else return HTML::Warning("Please fill all fields correctly!");
		} catch(\Exception $ex) { return HTML::Error($ex); }
		else return HTML::Warning("You are logged in!");
    }
}
?>