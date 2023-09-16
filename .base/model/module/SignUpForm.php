<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
use MiMFa\Library\User;
use MiMFa\Library\Convert;
MODULE("Form");
class SignUpForm extends Form{
	public $Capturable = true;
	public $Action = null;
	public $Title = "Sign Up";
	public $Image = "user";
	public $SubmitLabel = "Register";
	public $SignatureLabel = "<i class='fa fa-sign-in'></i>";
	public $FirstNameLabel = "<i class='fa fa-user'></i>";
	public $LastNameLabel = "<i class='fa fa-user'></i>";
	public $EmailLabel = "<i class='fa fa-envelope'></i>";
	public $ContactLabel = "<i class='fa fa-phone-square'></i>";
	public $RouteLabel = "<i class='fa fa-route'></i>";
	public $PasswordLabel = "<i class='fa fa-lock'></i>";
	public $PasswordConfirmationLabel = "<i class='fa fa-lock'></i>";
	public $SignaturePlaceHolder = "UserName";
	public $FirstNamePlaceHolder = "First Name";
	public $LastNamePlaceHolder = "Last Name";
	public $EmailPlaceHolder = "Email Address";
	public $ContactPlaceHolder = "Phone Number";
	public $PasswordPlaceHolder = "Password";
	public $PasswordConfirmationPlaceHolder = "Confirm Password";
	public $RoutePlaceHolder = "Introduction Method";
	public $SignInLabel = "Do you have an account?";
	public $WelcomeFormat = '<div class="welcome result success"><img class="welcome" src="$IMAGE"><p class="welcome">Hello $NAME,<br>You are signed in now, also there you can sign with another account!</p></div>';
	public $HasInternalMethod = true;
	public $HasExternalMethod = false;
	public $MultipleSignIn = false;
	public $UpdateMode = false;
	/**
	 * Account needs to confirm throght activation email way
	 * @var bool
	 */
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

	public function __construct(){
        parent::__construct();
		$this->Action = User::$UpHandlerPath;
		$this->InitialStatus = User::$InitialStatus;
		$this->SendActivationEmail = User::$InitialStatus < User::$ActiveStatus;
	}

	public function GetStyle(){
		if($this->HasDecoration) return parent::GetStyle().HTML::Style("
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
			.{$this->Name} div.welcome img.welcome {
				width: 50%;
				max-width: 300px;
				border-radius: 100%;
			}
			.{$this->Name} div.welcome p.welcome {
				text-align: center;
			}
		");
		else return parent::GetStyle();
	}

	public function Get(){
		if(getAccess(\_::$CONFIG->UserAccess) && !$this->MultipleSignIn)
			return Convert::FromDynamicString($this->WelcomeFormat);
		else return parent::Get();
	}
	public function GetHeader(){
        if(getAccess(\_::$CONFIG->UserAccess))
			return parent::GetHeader().Convert::FromDynamicString($this->WelcomeFormat);
		return parent::GetHeader();
    }
	public function GetFields(){
		yield parent::GetFields();
        if($this->HasInternalMethod){
			yield HTML::Rack(
				HTML::LargeSlot(
					HTML::Label($this->FirstNameLabel, "FirstName", ["class"=>"prepend"]).
					HTML::ValueInput("FirstName", ["placeholder"=> $this->FirstNamePlaceHolder])
				, ["class"=>"field"]).
				HTML::LargeSlot(
					HTML::Label($this->LastNameLabel, "LastName", ["class"=>"prepend"]).
					HTML::ValueInput("LastName", ["placeholder"=> $this->LastNamePlaceHolder])
				, ["class"=>"field"])
			);
			yield HTML::Rack(
				HTML::LargeSlot(
					HTML::Label($this->EmailLabel, "Email", ["class"=>"prepend"]).
					HTML::EmailInput("Email", ["placeholder"=> $this->EmailPlaceHolder])
				, ["class"=>"field"])
			);
			yield HTML::Rack(
				HTML::LargeSlot(
					HTML::Label($this->ContactLabel, "Phone", ["class"=>"prepend"]).
					HTML::SelectInput("CountryCode", "+1",
						function(){for ($i = 0; $i < 100; $i++) yield "<option value='+$i'>+$i</option>";}).
					HTML::TelInput("Phone", ["placeholder"=> $this->ContactPlaceHolder])
				, ["class"=>"field"])
			);
			yield HTML::Rack(
				HTML::LargeSlot(
					HTML::Label($this->RouteLabel, "Route", ["class"=>"prepend"]).
					HTML::SelectInput("Route", "None",[
						"None"=>$this->RoutePlaceHolder,
						"Metting"=>"Metting",
						"Card"=>"Visit Card",
						"Advertisement"=>"Advertisement",
						"Search"=>"Search Engine",
						"Social"=>"Social Media",
						"Other"=>"Other"
					])
				, ["class"=>"field"])
			);
			yield HTML::Rack(
				HTML::LargeSlot(
					HTML::Label($this->SignatureLabel, "UserName", ["class"=>"prepend"]).
					HTML::ValueInput("UserName", ["placeholder"=> $this->SignaturePlaceHolder])
				, ["class"=>"field", "autocomplete"=>"username"])
			);
			yield HTML::Rack(
				HTML::LargeSlot(
					HTML::Label($this->PasswordLabel, "Password", ["class"=>"prepend"]).
					HTML::SecretInput("Password", ["placeholder"=> $this->PasswordPlaceHolder])
				, ["class"=>"field", "autocomplete"=>"password"]).
				HTML::LargeSlot(
					HTML::Label($this->PasswordConfirmationLabel, "PasswordConfirmation", ["class"=>"prepend"]).
					HTML::SecretInput("PasswordConfirmation", ["placeholder"=> $this->PasswordConfirmationPlaceHolder])
				, ["class"=>"field", "autocomplete"=>"password"])
			);
		}
		if($this->HasExternalMethod){
		}
    }
	public function GetFooter(){
        return parent::GetFooter().HTML::LargeSlot(
			HTML::Link($this->SignInLabel, User::$InHandlerPath)
			, ["class"=>"col"]);
    }

	public function GetScript(){
        return HTML::Script("
			$(function () {
                $('.{$this->Name} form').submit(function(e) {
					if ($('.{$this->Name} form #PasswordConfirmation').val() == $('.{$this->Name} form #Password').val()) return true;
					$('.{$this->Name} form result').remove();
					$('.{$this->Name} form').append(Html.error('New password and confirm password does not match!'));
					e.preventDefault();
					return false;
                });
			});
		").parent::GetScript();
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
			if(isValid($_req,"Email") || isValid($_req,"Password"))
			{
				if(\_::$INFO->User->SignUp(
					getValid($_req,"UserName"),
					getValid($_req,"Password"),
					getValid($_req,"Email"),
					null,
					getValid($_req,"FirstName"),
					getValid($_req,"LastName"),
					getValid($_req,"CountryCode").getValid($_req,"Phone"),
					$this->GroupID,
					$this->InitialStatus
				))
				{
					return HTML::Success("Dear '".\_::$INFO->User->TemporaryName."', You registered successfully").
					($this->SendActivationEmail? PART(User::$ActiveHandlerPath, print:false) : "");
				}
				else return HTML::Error("The user with these email or username could not register!");
			}
			else return HTML::Warning("Please fill all required fields correctly!");
		} catch(\Exception $ex) { return HTML::Error($ex); }
		else return HTML::Warning("You are logged in!");
		return null;
    }
}
?>