<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;

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
		$this->Action = \User::$RecoverHandlerPath;
		$this->SuccessPath = \User::$InHandlerPath;
	}

	public function GetFields(){
		if(!is_null($rrk = receive(\User::$RecoveryTokenKey))){
			yield Html::HiddenInput(\User::$RecoveryTokenKey, $rrk);
			yield Html::Rack(
				Html::LargeSlot(
					Html::Label($this->PasswordLabel, "Password" , ["class"=>"prepend"]).
					Html::SecretInput("Password" , ["placeholder"=> $this->PasswordPlaceHolder, "autocomplete"=>"Password"])
				, ["class"=>"field"])
			);
			yield Html::Rack(
				Html::LargeSlot(
					Html::Label($this->PasswordConfirmationLabel, "PasswordConfirmation", ["class"=>"prepend"]).
					Html::SecretInput("PasswordConfirmation", ["placeholder"=> $this->PasswordConfirmationPlaceHolder, "autocomplete"=>"Password"])
				, ["class"=>"field"])
			);
        }else{
			yield Html::Rack(
				Html::LargeSlot(
					Html::Label($this->SignatureLabel, "Signature" , ["class"=>"prepend"]).
					Html::TextInput("Signature" , ["placeholder"=> $this->SignaturePlaceHolder, "autocomplete"=>"username"])
				, ["class"=>"field"])
			);
		}
		yield from parent::GetFields();
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
	public function GetFooter(){
		if(auth(\_::$Config->UserAccess)) return parent::GetFooter();
        else return parent::GetFooter()
			.Html::LargeSlot(
				Html::Link($this->SignInLabel, $this->SignInPath??\User::$InHandlerPath)
			, ["class"=>"col-lg-12"])
			.Html::LargeSlot(
				Html::Link($this->SignUpLabel, $this->SignUpPath??\User::$UpHandlerPath)
			, ["class"=>"col-lg-12"]);
    }

	public function Post(){
		try {
			$received = receivePost();
			if(isValid($received, "Password" ) && receive(\User::$RecoveryTokenKey)){
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
?>