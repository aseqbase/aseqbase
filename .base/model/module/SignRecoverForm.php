<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
module("Form");
class SignRecoverForm extends Form{
	public $Action = null;
	public $Title = "Account Recovery";
	public $Image = "undo-alt";
	public $SubmitLabel = "Submit";
	public $SignatureLabel = "<i class='fa fa-sign-in'></i>";
	public $PasswordLabel = "<i class='fa fa-lock'></i>";
	public $PasswordConfirmationLabel = "<i class='fa fa-lock'></i>";
	public $SignaturePlaceHolder = "Email/Phone";
	public $PasswordPlaceHolder = "Password";
	public $PasswordConfirmationPlaceHolder = "Confirm Password";
	public $BlockTimeout = 30000;
	public $ResponseView = null;

	public function __construct(){
        parent::__construct();
		$this->Action = \MiMFa\Library\User::$RecoverHandlerPath;
		$this->SuccessPath = \MiMFa\Library\User::$InHandlerPath;
	}

	public function GetFields(){
		if(!is_null($rrk = \Req::Receive(\MiMFa\Library\User::$RecoveryRequestKey))){
			yield Html::HiddenInput(\MiMFa\Library\User::$RecoveryRequestKey, $rrk);
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
					Html::ValueInput("Signature" , ["placeholder"=> $this->SignaturePlaceHolder, "autocomplete"=>"username"])
				, ["class"=>"field"])
			);
		}
		yield from parent::GetFields();
    }

	public function GetScript(){
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
		").parent::GetScript();
    }

	public function Post(){
		try {
			$received = \Req::Post();
			if(isValid($received, "Password" ) && \Req::Receive(\MiMFa\Library\User::$RecoveryRequestKey)){
				$res = \_::$Back->User->ReceiveRecoveryLink();
				if($res === true)
                	return $this->GetSuccess("Dear '".\_::$Back->User->TemporaryName."', your password changed successfully!");
				elseif($res === false)
					return $this->GetError("There a problem is occured!");
				else
					return $this->GetError($res);
			}
			elseif(isValid($received,"Signature" )){
				\_::$Back->User->Find(get($received,"Signature" ));
				$res = \_::$Back->User->SendRecoveryEmail();
				if($res === true)
                	return $this->GetSuccess("Dear user, the reset password sent to your email successfully!");
				elseif($res === false)
					return $this->GetError("There a problem is occured!");
				else
					return $this->GetError($res);
			}
			else return $this->GetWarning("Please fill fields correctly!");
		} catch(\Exception $ex) { return $this->GetError($ex); }
    }
}
?>