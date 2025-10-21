<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;

use MiMFa\Library\Convert;
module("Form");
class SignUpForm extends Form
{
	public $Action = null;
	public $Title = "Sign Up";
	public $Image = "user-plus";
	public $SubmitLabel = "Register";
	public $SignatureLabel = "<i class='icon fa fa-sign-in'></i>";
	public $SignatureValue = null;
	public $FirstNameLabel = "<i class='icon fa fa-user'></i>";
	public $LastNameLabel = "<i class='icon fa fa-user'></i>";
	public $EmailLabel = "<i class='icon fa fa-envelope'></i>";
	public $GroupLabel = "<i class='icon fa fa-group'></i>";
	public $GroupValue = null;
	public $RouteLabel = "<i class='icon fa fa-route'></i>";
	public $ContactLabel = "<i class='icon fa fa-phone-square'></i>";
	public $PasswordLabel = "<i class='icon fa fa-lock'></i>";
	public $PasswordValue = null;
	public $PasswordConfirmationLabel = "<i class='icon fa fa-lock'></i>";
	public $SignaturePlaceHolder = "Indicate a unique username";
	public $FirstNamePlaceHolder = "Your first name";
	public $LastNamePlaceHolder = "Your last name";
	public $EmailPlaceHolder = "Your valid email address";
	public $GroupPlaceHolder = null;
	public $RoutePlaceHolder = null;//"<i class='icon fa fa-route'></i>";
	public $ContactPlaceHolder = "A valid phone number";
	public $PasswordPlaceHolder = "A strong password";
	public $PasswordConfirmationPlaceHolder = "Confirm your password";
	/**
	 * Indicate all available groups for each new registerant
	 * @var array|null
	 */
	public $GroupOptions = null;/*[
		"2", "Guest",
		"3", "Registered",
		"4", "Student"
	];*/
	/**
	 * All available route options
	 * @var array|null
	 */
	public $RouteOptions = null;/*[
		"None" => "How you meet us?",
		"Metting" => "Metting",
		"Card" => "Visit Card",
		"Advertisement" => "Advertisement",
		"Search" => "Search Engine",
		"Social" => "Social Media",
		"Other" => "Other"
	];*/
	public $SignaturePattern = "/[^\"'`]{5,100}/";
	public $SignatureTip = "Your username should be unique and between 5-100 characters!";
	public $PasswordPattern = "/[^\"'`]{8,100}/";
	public $PasswordTip = "Your password should be strong and between 8-100 characters!";
	public $SignInLabel = "Do you have an account?";
	public $SignInPath = null;
	public $Welcome = null;
	public $WelcomeFormat = null;//'<div class="welcome result success"><br><p class="welcome">Hello dear "$NAME",<br>You are signed in now!</p></div>';
	public $ContactCountryCode = null;
	public $AllowInternalMethod = true;
	public $AllowExternalMethod = false;
	public $MultipleSignIn = false;
	public $UpdateMode = false;
	public $DefaultGroupId = null;
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
		$this->Action = \_::$User->UpHandlerPath;
		$this->InitialStatus = \_::$User->InitialStatus;
		$this->Welcome = function(){ return part(\_::$User->DashboardHandlerPath, print:false); };
	}

	public function GetStyle()
	{
		if ($this->AllowDecoration)
			return parent::GetStyle() . Html::Style("
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
		if (\_::$User->GetAccess(\_::$User->UserAccess) && !$this->MultipleSignIn)
			return $this->GetHeader().Convert::ToString($this->Welcome);
		else
			return parent::Get();
	}
	public function GetHeader()
	{
        if(\_::$User->GetAccess(\_::$User->UserAccess) && !isEmpty($this->WelcomeFormat))
			return __(Convert::FromDynamicString($this->WelcomeFormat));
		return parent::GetHeader();
	}
	public function GetFields()
	{
		if ($this->AllowInternalMethod) {
			yield Html::Rack(
				(isValid($this->FirstNameLabel) ? Html::LargeSlot(
					Html::Label($this->FirstNameLabel, "FirstName", ["class"=> "prepend"]) .
					Html::TextInput("FirstName", ["placeholder" => $this->FirstNamePlaceHolder]),
					["class"=> "field"]
				) : "") .
				(isValid($this->LastNameLabel) ? Html::LargeSlot(
					Html::Label($this->LastNameLabel, "LastName", ["class"=> "prepend"]) .
					Html::TextInput("LastName", ["placeholder" => $this->LastNamePlaceHolder]),
					["class"=> "field"]
				) : "")
			);
			if (isValid($this->EmailLabel))
				yield Html::Rack(
					Html::LargeSlot(
						Html::Label($this->EmailLabel, "Email", ["class"=> "prepend"]) .
						Html::EmailInput("Email", ["placeholder" => $this->EmailPlaceHolder]),
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
						Html::TelInput("Phone", ["placeholder" => $this->ContactPlaceHolder]),
						["class"=> "field"]
					)
				);
			if (isValid($this->GroupLabel) && !isEmpty($this->GroupOptions))
				yield Html::Rack(
					Html::LargeSlot(
						Html::Label($this->GroupLabel, "Group", ["class"=> "prepend"]) .
						Html::SelectInput("Group", $this->GroupValue, $this->GroupPlaceHolder, $this->GroupOptions),
						["class"=> "field"]
					)
				);
			if (isValid($this->RouteLabel) && !isEmpty($this->RouteOptions))
				yield Html::Rack(
					Html::LargeSlot(
						Html::Label($this->RouteLabel, "Route", ["class"=> "prepend"]) .
						Html::SelectInput("Route", $this->RoutePlaceHolder, $this->RouteOptions),
						["class"=> "field"]
					)
				);
			if (isValid($this->SignatureLabel))
				yield Html::Rack(
					Html::LargeSlot(
						Html::Label($this->SignatureLabel, "Signature", ["class"=> "prepend"]) .
						Html::TextInput("Signature", $this->SignatureValue, ["placeholder" => $this->SignaturePlaceHolder, "autocomplete" => "UserName"]),
						["class"=> "field"]
					)
				);
			if (isValid($this->PasswordLabel))
				yield Html::Rack(
					Html::LargeSlot(
						Html::Label($this->PasswordLabel, "Password" , ["class"=> "prepend"]) .
						Html::SecretInput("Password", $this->PasswordValue, ["placeholder" => $this->PasswordPlaceHolder, "autocomplete" => "Password"]),
						["class"=> "field"]
					) .
					Html::LargeSlot(
						Html::Label($this->PasswordConfirmationLabel, "PasswordConfirmation", ["class"=> "prepend"]) .
						Html::SecretInput("PasswordConfirmation", $this->PasswordValue, ["placeholder" => $this->PasswordConfirmationPlaceHolder, "autocomplete" => "Password"]),
						["class"=> "field"]
					)
				);
		}
		if ($this->AllowExternalMethod) {
		}
		yield from parent::GetFields();
	}
	public function GetFooter()
	{
		return parent::GetFooter() . Html::LargeSlot(
			Html::Link($this->SignInLabel, $this->SignInPath??\_::$User->InHandlerPath)
			,
			["class"=> "col"]
		);
	}

	public function GetScript()
	{
		return Html::Script("
			$(function () {
				$(`.{$this->Name} :is(input, select, textarea)`).on('focus', function () {
					$(this).parent().find(`.{$this->Name} .input-group .text`).css('outline-color', 'var(--fore-color-output)');
				});
				$(`.{$this->Name} :is(input, select, textarea)`).on('blur', function () {
					$(this).parent().find(`.{$this->Name} .input-group .text`).css('outline-color', 'var(--fore-color-output)');
				});
                $('.{$this->Name} form').submit(function(e) {
					let error = null;
					if (!$('.{$this->Name} form [name=Password]')?.val().match({$this->PasswordPattern})) 
						error = Html.error(".\MiMFa\Library\Script::Convert($this->PasswordTip).");
					else if (!$('.{$this->Name} form [name=Signature]')?.val().match({$this->SignaturePattern})) 
						error = Html.error(".\MiMFa\Library\Script::Convert($this->SignatureTip).");
					else if ($('.{$this->Name} form [name=PasswordConfirmation]')?.val() != $('.{$this->Name} form [name=Password]')?.val()) 
						error = Html.error('New password and confirm password does not match!');
					if(error) {
						$('.{$this->Name} form .result').remove();
						$('.{$this->Name} form').append(error);
						e.preventDefault();
						return false;
					}
					" . ($this->UseAjax ? "submitForm('.{$this->Name} form', null, null, null, null, ".($this->Timeout*1000).");" : "") . "
					return true;
                });
			});
		");
	}

	public function Post()
	{
		if (!\_::$User->GetAccess(\_::$User->UserAccess))
			try {
				$received = receivePost();
				if (isValid($received, "Email") || isValid($received, "Password" )) {
					$signature = get($received, "Signature");
					if (!preg_match($this->SignaturePattern,$signature))
						return $this->GetError($this->SignatureTip);
					$password = get($received, "Password" );
					if (!preg_match($this->PasswordPattern, $password)) 
						return $this->GetError($this->PasswordTip);
					$route = get($received, "Route");
					$group = get($received, "Group");
					if(!isset($this->GroupOptions[$group])) $group = $this->DefaultGroupId;
				
					if (
						\_::$User->SignUp(
							signature: $signature,
							password: $password,
							email: get($received, "Email"),
							name: null,
							firstName: get($received, "FirstName"),
							lastName: get($received, "LastName"),
							phone: (get($received, "CountryCode")??"0") . " " . get($received, "Phone"),
							groupId: $group,
							status: $this->InitialStatus,
							metadata: $route?["Route"=>$route]:null
						) !== false
					) {
						$this->Result = true;
						return $this->GetSuccess("Dear '" . \_::$User->TemporaryName . "', You registered successfully");
					} else
						return $this->GetError("The user can not register with this email or username!");
				} else
					return $this->GetWarning("Please fill all required fields correctly!");
			} catch (\Exception $ex) {
				return $this->GetError($ex);
			} else
			return $this->GetWarning("You are logged in!");
	}
}