<?php namespace MiMFa\Module;
MODULE("Form");
class ResetPasswordForm extends Form{
	public $Action = null;
	public $Title = "Reset Password";
	public $SubmitLabel = "Submit";
	public $SignatureLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-sign-in text-muted'></i>
		</span>";
	public $PasswordLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-lock text-muted'></i>
		</span>";
	public $PasswordConfirmationLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-lock text-muted'></i>
		</span>";
	public $SignaturePlaceHolder = "Email/Phone";
	public $PasswordPlaceHolder = "Password";
	public $PasswordConfirmationPlaceHolder = "Confirm Password";
	
	public function __construct(){
        parent::__construct();
		$this->Action = \MiMFa\Library\User::$ResetHandlerPath;
	}

	public function EchoFields(){
		if(isValid($_REQUEST, \MiMFa\Library\User::$ResetRequestKey)):
			echo '<input type="hidden" name="'.\MiMFa\Library\User::$ResetRequestKey.'" value="'.getValid($_REQUEST, \MiMFa\Library\User::$ResetRequestKey).'">';
			?>
			<div class="row">
				<!-- Password -->
				<div class="input-group col-lg-12 mb-4">
					<label for="password" class="input-group-prepend">
						<?php echo $this->PasswordLabel; ?>
					</label>
					<input id="password" type="password" name="password" placeholder="<?php echo __($this->PasswordPlaceHolder);?>" aria-label="<?php echo __($this->PasswordPlaceHolder);?>" class="form-control bg-white border-left-0 border-md">
				</div>

				<!-- PasswordConfirmation -->
				<div class="input-group col-lg-12 mb-4">
					<label for="passwordConfirmation" class="input-group-prepend">
						<?php echo $this->PasswordConfirmationLabel; ?>
					</label>
					<input id="passwordConfirmation" type="password" name="passwordConfirmation" placeholder="<?php echo __($this->PasswordConfirmationPlaceHolder);?>" aria-label="<?php echo __($this->PasswordConfirmationPlaceHolder);?>" class="form-control bg-white border-left-0 border-md">
				</div>
			</div>
			<?php
		else:?>
			<div class="row">
				<!-- Signature Address -->
				<div class="input-group col-lg-12 mb-4">
					<label for="username" class="input-group-prepend">
						<?php echo $this->SignatureLabel; ?>
					</label>
					<input id="username" type="text" name="username" autocomplete="username"
							placeholder="<?php echo __($this->SignaturePlaceHolder);?>"
							aria-label="<?php echo __($this->SignaturePlaceHolder);?>"
							class="form-control bg-white border-left-0 border-md">
				</div>
			</div>
		<?php endif;
    }
	
	public function EchoScript(){
		?>
		<script>
			$(function () {
                $(".<?php echo $this->Name ?> form").submit(function(e) {
					if ($(".<?php echo $this->Name ?> form #passwordConfirmation").val() == $(".<?php echo $this->Name ?> form #password").val()) return true;
					$(".<?php echo $this->Name ?> form result").remove();
					$(".<?php echo $this->Name ?> form").append(Html.error("New password and confirm password does not match!"));
					e.preventDefault();
					return false;
                });
			});
		</script>
		<?php
		parent::EchoScript();
    }

	public function Action(){
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
			if(isValid($_req,"password") && isValid($_REQUEST, \MiMFa\Library\User::$ResetRequestKey)){
				$res = \_::$INFO->User->ReceiveResetPasswordLink();
				if($res === true)
                	echo "<div class='page result success'>".__("Dear '".\_::$INFO->User->TemporaryName."', your password changed successfully!")."</div>";
				elseif($res === false)
					echo "<div class='result error'>".__("There a problem is occured!")."</div>";
				else
					echo "<div class='result error'>".__($res)."</div>";
			}
			elseif(isValid($_req,"username")){
				\_::$INFO->User->GetUser(getValid($_req,"username"));
				$res = \_::$INFO->User->SendResetPasswordEmail();
				if($res === true)
                	echo "<div class='page result success'>".__("Dear user, the reset password sent to your email successfully!")."</div>";
				elseif($res === false)
					echo "<div class='result error'>".__("There a problem is occured!")."</div>";
				else
					echo "<div class='result error'>".__($res)."</div>";
			}
			else echo "<div class='result warning'>".__("Please fill fields correctly!")."</div>";
		} catch(\Exception $ex) { echo "<div class='result error'>".__($ex->getMessage())."</div>"; }
    }
}
?>