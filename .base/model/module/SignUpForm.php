<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\User;
use MiMFa\Library\Convert;
module("Form");
class SignUpForm extends Form
{
	public $Action = null;
	public $Title = "Sign Up";
	public $Image = "user";
	public $SubmitLabel = "register";
	public $SignatureLabel = "<i class='fa fa-sign-in'></i>";
	public $FirstNameLabel = "<i class='fa fa-user'></i>";
	public $LastNameLabel = "<i class='fa fa-user'></i>";
	public $EmailLabel = "<i class='fa fa-envelope'></i>";
	public $ContactLabel = "<i class='fa fa-phone-square'></i>";
	public $RouteLabel = null;//"<i class='fa fa-route'></i>";
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
	public $Welcome = null;
	public $WelcomeFormat = null;//'<div class="welcome result success"><br><p class="welcome">Hello dear "$NAME",<br>You are signed in now!</p></div>';
	public $ContactCountryCode = null;
	public $HasInternalMethod = true;
	public $HasExternalMethod = false;
	public $MultipleSignIn = false;
	public $UpdateMode = false;
	/**
	 * Account needs to confirm throght activation email way
	 * @var bool
	 */
	public $SendActivationEmail = true;
	public $GroupId = null;
	/**
	 * Initial User Status:
	 *		true/1:		Activated
	 *		null/0:		Default Action
	 *		false/-1:		Deactivated
	 * @var bool|null
	 */
	public $InitialStatus = null;
	public $BlockTimeout = 60000;
	public $ResponseView = null;

	public function __construct()
	{
		parent::__construct();
		$this->Action = User::$UpHandlerPath;
		$this->InitialStatus = User::$InitialStatus;
		$this->SendActivationEmail = User::$InitialStatus < User::$ActiveStatus;
		$this->Welcome = function(){ return part(User::$DashboardHandlerPath, print:false); };
	}

	public function GetStyle()
	{
		if ($this->HasDecoration)
			return parent::GetStyle() . Html::Style("
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
		else
			return parent::GetStyle();
	}

	public function Get()
	{
		if (auth(\_::$Config->UserAccess) && !$this->MultipleSignIn)
			return $this->GetHeader().Convert::ToString($this->Welcome);
		else
			return parent::Get();
	}
	public function GetHeader()
	{
        if(auth(\_::$Config->UserAccess) && !isEmpty($this->WelcomeFormat))
			return __(Convert::FromDynamicString($this->WelcomeFormat));
		return parent::GetHeader();
	}
	public function GetFields()
	{
		if ($this->HasInternalMethod) {
			yield Html::Rack(
				(isValid($this->FirstNameLabel) ? Html::LargeSlot(
					Html::Label($this->FirstNameLabel, "FirstName", ["class"=> "prepend"]) .
					Html::ValueInput("FirstName", ["placeholder" => $this->FirstNamePlaceHolder])
					,
					["class"=> "field"]
				) : "") .
				(isValid($this->LastNameLabel) ? Html::LargeSlot(
					Html::Label($this->LastNameLabel, "LastName", ["class"=> "prepend"]) .
					Html::ValueInput("LastName", ["placeholder" => $this->LastNamePlaceHolder])
					,
					["class"=> "field"]
				) : "")
			);
			if (isValid($this->EmailLabel))
				yield Html::Rack(
					Html::LargeSlot(
						Html::Label($this->EmailLabel, "Email", ["class"=> "prepend"]) .
						Html::EmailInput("Email", ["placeholder" => $this->EmailPlaceHolder])
						,
						["class"=> "field"]
					)
				);
			if (isValid($this->ContactLabel))
				yield Html::Rack(
					Html::LargeSlot(
						Html::Label($this->ContactLabel, "Phone", ["class"=> "prepend"]) .
						(isValid($this->ContactCountryCode) ? Html::SelectInput(
							"CountryCode",
							"+1",
							function () {
								for ($i = 0; $i < 100; $i++)
									yield "<option value='+$i'" . ($this->ContactCountryCode == $i ? " selected" : "") . ">+$i</option>"; }
						)
							: "") .
						Html::TelInput("Phone", ["placeholder" => $this->ContactPlaceHolder])
						,
						["class"=> "field"]
					)
				);
			if (isValid($this->RouteLabel))
				yield Html::Rack(
					Html::LargeSlot(
						Html::Label($this->RouteLabel, "Route", ["class"=> "prepend"]) .
						Html::SelectInput("Route", "None", [
							"None" => $this->RoutePlaceHolder,
							"Metting" => "Metting",
							"Card" => "Visit Card",
							"Advertisement" => "Advertisement",
							"Search" => "Search Engine",
							"Social" => "Social Media",
							"Other" => "Other"
						])
						,
						["class"=> "field"]
					)
				);
			if (isValid($this->SignatureLabel))
				yield Html::Rack(
					Html::LargeSlot(
						Html::Label($this->SignatureLabel, "username", ["class"=> "prepend"]) .
						Html::ValueInput("username", ["placeholder" => $this->SignaturePlaceHolder, "autocomplete" => "username"])
						,
						["class"=> "field"]
					)
				);
			if (isValid($this->PasswordLabel))
				yield Html::Rack(
					Html::LargeSlot(
						Html::Label($this->PasswordLabel, "Password" , ["class"=> "prepend"]) .
						Html::SecretInput("Password" , ["placeholder" => $this->PasswordPlaceHolder, "autocomplete" => "Password"])
						,
						["class"=> "field"]
					) .
					Html::LargeSlot(
						Html::Label($this->PasswordConfirmationLabel, "PasswordConfirmation", ["class"=> "prepend"]) .
						Html::SecretInput("PasswordConfirmation", ["placeholder" => $this->PasswordConfirmationPlaceHolder, "autocomplete" => "Password"])
						,
						["class"=> "field"]
					)
				);
		}
		if ($this->HasExternalMethod) {
		}
		yield from parent::GetFields();
	}
	public function GetFooter()
	{
		return parent::GetFooter() . Html::LargeSlot(
			Html::Link($this->SignInLabel, User::$InHandlerPath)
			,
			["class"=> "col"]
		);
	}

	public function GetScript()
	{
		return Html::Script("
			$(function () {
                $('.{$this->Name} form').submit(function(e) {
					if ($('.{$this->Name} form #PasswordConfirmation').val() == $('.{$this->Name} form #Password').val()) return true;
					$('.{$this->Name} form result').remove();
					$('.{$this->Name} form').append(Html.error('New password and confirm password does not match!'));
					e.preventDefault();
					return false;
                });
			});
		") . parent::GetScript();
	}

	public function Post()
	{
		if (!auth(\_::$Config->UserAccess))
			try {
				$received = \Req::Post();
				if (isValid($received, "Email") || isValid($received, "Password" )) {
					if (
						\_::$Back->User->SignUp(
							signature: get($received, "username"),
							password: get($received, "Password" ),
							email: get($received, "Email"),
							name: null,
							firstName: get($received, "FirstName"),
							lastName: get($received, "LastName"),
							phone: findValid($received, "CountryCode", "0") . get($received, "Phone"),
							groupId: $this->GroupId,
							status: $this->InitialStatus
						)
					) {
						return $this->GetSuccess("Dear '" . \_::$Back->User->TemporaryName . "', You registered successfully") .
							($this->SendActivationEmail ? part(User::$ActiveHandlerPath, print: false) : "");
					} else
						return $this->GetError("The user cannot register with this email or username!");
				} else
					return $this->GetWarning("Please fill all required fields correctly!");
			} catch (\Exception $ex) {
				return $this->GetError($ex);
			} else
			return $this->GetWarning("You are logged in!");
	}
}
?>