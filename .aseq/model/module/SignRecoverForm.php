<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;

module("Form");
class SignRecoverForm extends Form{
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

	public function __construct(){
        parent::__construct();
		$this->Action = \_::$User->RecoverHandlerPath;
		$this->SuccessPath = \_::$User->InHandlerPath;
	}

	public function GetFields(){
		if(!is_null($rrk = getReceived(\_::$User->RecoveryTokenKey))){
			yield Struct::HiddenInput(\_::$User->RecoveryTokenKey, $rrk);
			yield Struct::Rack(
				Struct::LargeSlot(
					Struct::Label($this->PasswordLabel, "Password" , ["class"=>"prepend"]).
					Struct::SecretInput("Password" , ["placeholder"=> $this->PasswordPlaceHolder, "autocomplete"=>"Password"])
				, ["class"=>"field"])
			);
			yield Struct::Rack(
				Struct::LargeSlot(
					Struct::Label($this->PasswordConfirmationLabel, "PasswordConfirmation", ["class"=>"prepend"]).
					Struct::SecretInput("PasswordConfirmation", ["placeholder"=> $this->PasswordConfirmationPlaceHolder, "autocomplete"=>"Password"])
				, ["class"=>"field"])
			);
        }else{
			yield Struct::Rack(
				Struct::LargeSlot(
					Struct::Label($this->SignatureLabel, "Signature" , ["class"=>"prepend"]).
					Struct::TextInput("Signature" , ["placeholder"=> $this->SignaturePlaceHolder, "autocomplete"=>"username"])
				, ["class"=>"field"])
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
                _('.{$this->Name} form').submit(function(e) {
					let error = null;
					if (!_('.{$this->Name} form [name=Password]')?.val().match({$this->PasswordPattern})) 
						error = Struct.error(".\MiMFa\Library\Script::Convert($this->PasswordTip).");
					else if (_('.{$this->Name} form [name=PasswordConfirmation]')?.val() != _('.{$this->Name} form [name=Password]')?.val()) 
						error = Struct.error('New password and confirm password does not match!');
					e.preventDefault();
					if(error) {
						_('.{$this->Name} form .result').remove();
						_('.{$this->Name} form').append(error);
						return false;
					}
					" . ($this->Interactive ? "submitForm('.{$this->Name} form', null, null, null, null, ".($this->Timeout*1000).");" : "") . "
					return true;
                });
			});
		");
	}
	public function GetFooter(){
		if(\_::$User->HasAccess(\_::$User->UserAccess)) return parent::GetFooter();
        else return parent::GetFooter()
			.Struct::LargeSlot(
				Struct::Link($this->SignInLabel, $this->SignInPath??\_::$User->InHandlerPath)
			, ["class"=>"col-lg-12"])
			.Struct::LargeSlot(
				Struct::Link($this->SignUpLabel, $this->SignUpPath??\_::$User->UpHandlerPath)
			, ["class"=>"col-lg-12"]);
    }

	public function Post(){
		try {
			$received = receivePost();
			if(isValid($received, "Password" ) && getReceived(\_::$User->RecoveryTokenKey)){
				$res = \_::$User->ReceiveRecoveryEmail();
				if($res)
                	return $this->GetSuccess("Dear '".\_::$User->TemporaryName."', your password changed successfully!");
				else
					return $this->GetError("Something went wrong!");
			}
			elseif(isValid($received,"Signature" )){
				\_::$User->Find(get($received,"Signature" ));
				$res = \_::$User->SendRecoveryEmail();
				if($res === true)
                	return $this->GetSuccess("Dear user, the reset password sent to your email successfully!");
				elseif($res === false)
					return $this->GetError("Something went wrong!");
				else
					return $this->GetError($res);
			}
			else return $this->GetWarning("Please fill fields correctly!");
		} catch(\Exception $ex) { return $this->GetError($ex); }
    }
}