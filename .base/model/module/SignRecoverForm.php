<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
MODULE("Form");
class SignRecoverForm extends Form{
	public $Capturable = true;
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
		if(isValid($_REQUEST, \MiMFa\Library\User::$RecoveryRequestKey)){
			yield HTML::HiddenInput(\MiMFa\Library\User::$RecoveryRequestKey, getValid($_REQUEST, \MiMFa\Library\User::$RecoveryRequestKey));
			yield HTML::Rack(
				HTML::LargeSlot(
					HTML::Label($this->PasswordLabel, "Password", ["class"=>"prepend"]).
					HTML::SecretInput("Password", ["placeholder"=> $this->PasswordPlaceHolder])
				, ["class"=>"field", "autocomplete"=>"password"])
			);
			yield HTML::Rack(
				HTML::LargeSlot(
					HTML::Label($this->PasswordConfirmationLabel, "PasswordConfirmation", ["class"=>"prepend"]).
					HTML::SecretInput("PasswordConfirmation", ["placeholder"=> $this->PasswordConfirmationPlaceHolder])
				, ["class"=>"field", "autocomplete"=>"password"])
			);
        }else{
			yield HTML::Rack(
				HTML::LargeSlot(
					HTML::Label($this->SignatureLabel, "Signature", ["class"=>"prepend"]).
					HTML::ValueInput("Signature", ["placeholder"=> $this->SignaturePlaceHolder])
				, ["class"=>"field", "autocomplete"=>"username"])
			);
		}
		yield from parent::GetFields();
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

	public function Handler(){
		$_req = $_REQUEST;
		switch(strtolower($this->Method)){
            case "get":
				$_req = $_GET;
			break;
            case "post":
				$_req = $_POST;
			break;
        }
		try {
			if(isValid($_req,"Password") && isValid($_REQUEST, \MiMFa\Library\User::$RecoveryRequestKey)){
				$res = \_::$INFO->User->ReceiveRecoveryLink();
				if($res === true)
                	return HTML::Success("Dear '".\_::$INFO->User->TemporaryName."', your password changed successfully!");
				elseif($res === false)
					return HTML::Error("There a problem is occured!");
				else
					return HTML::Error($res);
			}
			elseif(isValid($_req,"Signature")){
				\_::$INFO->User->Find(getValid($_req,"Signature"));
				$res = \_::$INFO->User->SendRecoveryEmail();
				if($res === true)
                	return HTML::Success("Dear user, the reset password sent to your email successfully!");
				elseif($res === false)
					return HTML::Error("There a problem is occured!");
				else
					return HTML::Error($res);
			}
			else return HTML::Warning("Please fill fields correctly!");
		} catch(\Exception $ex) { return HTML::Error($ex); }
    }
}
?>