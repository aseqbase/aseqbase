<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;

module("Form");
class SignRecoverForm extends Form
{
	public $Action = null;
	public $Title = "Account Recovery";
	public $Image = "undo-alt";
	public $SubmitLabel = "Submit";
	public $SignatureLabel = "<i class='icon fa fa-sign-in'></i>";
	public $PasswordLabel = "<i class='icon fa fa-lock'></i>";
	public $PasswordConfirmationLabel = "<i class='icon fa fa-lock'></i>";
	public $SignInLabel = "I remembered my password!";
	public $SignInPath = null;
	public $SignUpLabel = "I don't have an account!";
	public $SignUpPath = null;
	public $SignaturePlaceHolder = "Email/Phone";
	public $PasswordPlaceHolder = "Password";
	public $PasswordConfirmationPlaceHolder = "Confirm Password";

	public $PasswordPattern = "/[^\"'`]{8,100}/";
	public $PasswordTip = "Your password should be strong and between 8-100 characters!";

	public $BlockTimeout = 30000;
	public $ResponseView = null;
	public $Printable = false;

	public $TokenKey = "rt";

	public $EmailSubject = 'Account Recovery Request';
	public $EmailContent = 'Hello dear $NAME,<br><br>
We received an account recovery request on $HOSTLINK for $EMAILLINK.<br>
This email address is associated with an account but no password is associated with it yet, so it can\'t be used to log in.<br>
Please $HYPERLINK or the below link if you want to reset your password... else ignore this message.<br>$LINK<br><br>
With Respect,<br>$HOSTLINK<br>$HOSTEMAILLINK';
	public $EmailLinkLabel = "CLICK ON THIS LINK";

	public function __construct()
	{
		parent::__construct();
		$this->Action = \_::$User->RecoverHandlerPath;
		$this->SuccessPath = \_::$User->InHandlerPath;
	}

	public function GetFields()
	{
		if (!is_null($rrk = getReceived($this->TokenKey))) {
			yield Struct::HiddenInput($this->TokenKey, $rrk);
			yield Struct::Rack(
				Struct::LargeSlot(
					Struct::Label($this->PasswordLabel, "Password", ["class" => "prepend"]) .
					Struct::SecretInput("Password", ["placeholder" => $this->PasswordPlaceHolder, "autocomplete" => "Password"])
					,
					["class" => "field"]
				)
			);
			yield Struct::Rack(
				Struct::LargeSlot(
					Struct::Label($this->PasswordConfirmationLabel, "PasswordConfirmation", ["class" => "prepend"]) .
					Struct::SecretInput("PasswordConfirmation", ["placeholder" => $this->PasswordConfirmationPlaceHolder, "autocomplete" => "Password"])
					,
					["class" => "field"]
				)
			);
		} else {
			yield Struct::Rack(
				Struct::LargeSlot(
					Struct::Label($this->SignatureLabel, "Signature", ["class" => "prepend"]) .
					Struct::TextInput("Signature", ["placeholder" => $this->SignaturePlaceHolder, "autocomplete" => "username"])
					,
					["class" => "field"]
				)
			);
		}
		yield from parent::GetFields();
	}

	public function GetScript()
	{
		return Struct::Script("
			_(document).ready(function () {
				_(`.{$this->Name} :is(input, select, textarea)`).on('focus', function () {
					_(this).parent().find(`.{$this->Name} .input-group .text`).css('outline-color', 'var(--fore-color-output)');
				});
				_(`.{$this->Name} :is(input, select, textarea)`).on('blur', function () {
					_(this).parent().find(`.{$this->Name} .input-group .text`).css('outline-color', 'var(--fore-color-output)');
				});
                if(_('.{$this->Name} form [name=Password]'))
					_('.{$this->Name} form').submit(function(e) {
					let error = null;
					if (!_('.{$this->Name} form [name=Password]')?.val().match({$this->PasswordPattern})) 
						error = Struct.error(" . \MiMFa\Library\Script::Convert($this->PasswordTip) . ");
					else if (_('.{$this->Name} form [name=PasswordConfirmation]')?.val() != _('.{$this->Name} form [name=Password]')?.val()) 
						error = Struct.error('New password and confirm password does not match!');
					if(error) {
						e.preventDefault();
						_('.{$this->Name} form .result')?.remove();
						_('.{$this->Name} form').append(error);
						return false;
					}
					return true;
                });
				else " . ($this->Interactive ? "handleForm('.{$this->Name} form', null, null, null, null, " . ($this->Timeout * 1000) . ");" : "") . "
			});
		");
	}
	public function GetFooter()
	{
		if (\_::$User->HasAccess(\_::$User->UserAccess))
			return parent::GetFooter();
		else
			return parent::GetFooter()
				. Struct::LargeSlot(
					Struct::Link($this->SignInLabel, $this->SignInPath ?? \_::$User->InHandlerPath)
					,
					["class" => "col-lg-12"]
				)
				. Struct::LargeSlot(
					Struct::Link($this->SignUpLabel, $this->SignUpPath ?? \_::$User->UpHandlerPath)
					,
					["class" => "col-lg-12"]
				);
	}

	public function Post()
	{
		try {
			$received = receivePost();
			if (isValid($received, "Password") && getReceived($this->TokenKey)) {
				$res = $this->ReceiveRecoveryEmail();
				if ($res)
					return $this->GetSuccess("Dear '" . \_::$User->TemporaryName . "', your password changed successfully!");
				else
					return $this->GetError("Something went wrong!");
			} elseif (isValid($received, "Signature")) {
				\_::$User->Find(get($received, "Signature"));
				$res = $this->SendRecoveryEmail();
				if ($res === true)
					return $this->GetSuccess("Dear user, the reset password sent to your email successfully!");
				elseif ($res === false)
					return $this->GetError("Something went wrong!");
				else
					return $this->GetError($res);
			} else
				return $this->GetWarning("Please fill fields correctly!");
		} catch (\Exception $ex) {
			return $this->GetError($ex);
		}
	}


	/**
	 * Send a Recovery Email
	 * @param string $emailFrom Sender
	 * @param string $emailTo Receiver
	 * @param string The email text or html contents, contains:
	 * $HYPERLINK: for the reset password hyperlink tag
	 * $LINK: for the reset password link
	 * $PATH: for the reset password link address
	 * $NAME: for the user name
	 * $SIGNATURE: for the user Signature
	 * $IMAGE: for the user image path
	 * @return bool
	 */
	public function SendRecoveryEmail($subject = null, $content = null, $linkAnchor = null)
	{
		return \_::$User->SendTokenEmail(
			$this->SenderEmail ?? \_::$Front->SenderEmail,
			$receiverEmail ?? \_::$User->TemporaryEmail,
			$subject ?? $this->EmailSubject,
			$content ?? $this->EmailContent,
			$linkAnchor ?? $this->EmailLinkLabel,
			\_::$User->RecoverHandlerPath,
			$this->TokenKey
		);
	}
	/**
	 * Receive the Recovery Email
	 * @return bool|null return true if user activated, null if request is not received or false otherwise
	 */
	public function ReceiveRecoveryEmail()
	{
		$newPass = getReceived("Password");
		if (isValid($newPass)) {
			\_::$User->EncryptPassword($newPass);
			$sign = \_::$User->DecryptToken($this->TokenKey, getReceived($this->TokenKey));
			if (empty($sign))
				return null;
			return \_::$User->ResetPassword($sign, $newPass) ? true : false;
		}
		return null;
	}

}