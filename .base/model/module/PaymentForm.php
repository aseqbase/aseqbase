<?php namespace MiMFa\Module;
MODULE("Form");
MODULE("QRCodeBox");
class PaymentForm extends Form{
	public $Title = "Payment";
	public $SubmitLabel = "Pay";
	public $Method = "POST";
	public QRCodeBox|null $QRCodeBox = null;

	
	public function __construct(){
        parent::__construct();
		$this->QRCodeBox = new QRCodeBox();
		$this->QRCodeBox->Height = "30vmin";
    }

	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name ?> .input-group-prepend{
				margin-bottom: 0px;
			}
			.<?php echo $this->Name ?> .btn{
				color: var(--ForeColor-2);
				background-color: var(--BackColor-2);
				border: none;
				padding: var(--Size-0) var(--Size-1);
			}

			.<?php echo $this->Name ?> .btn-main {
				color: var(--BackColor-2);
				background-color: var(--ForeColor-2);
			}

			.<?php echo $this->Name ?> .border-md {
				border-width: 2px;
			}
			.<?php echo $this->Name ?> .form-control:not(select) {
				padding: 1.5rem 0.5rem;
			}
			.<?php echo $this->Name ?> select.form-control {
				height: 52px;
				padding-left: 0.5rem;
			}

			.<?php echo $this->Name ?> .form-control::placeholder {
				color: #888;
				font-weight: bold;
				font-size: 0.9rem;
			}
			.<?php echo $this->Name ?> .form-control:focus {
				box-shadow: none;
			}
		</style>
		<?php
	}

	public function Echo(){
		$this->EchoContent();
		$src = $this->Action??$this->Path;
		if(isValid($src)): ?>
			<div class="page container">
				<div class="row align-items-center">
					<!-- For Demo Purpose -->
					<div class="col-md-5 pr-lg-5 mb-5 mb-md-0">
						<img src="<?php echo forceUrl($this->Image);?>" alt="" class="img-fluid mb-3 d-none d-md-block">
						<?php $this->EchoHeader();
							$this->EchoTitle();
							$this->EchoDescription();
						?>
						<a href="<?php echo __($this->BackLink);?>" class="text-muted">
							<?php echo __($this->BackLabel);?>
						</a>
					</div>

					<!-- Form -->
					<div class="col-md col-lg ml-auto">
						<form action="<?php echo $src; ?>" method="<?php echo $this->Method;?>">
							<?php $this->EchoFields();?>
								<!-- Submit Button -->
								<div class="form-group mx-auto mb-0 row">
									<?php if(isValid($this->ResetLabel)):?>
										<button type="reset" id="reset" name="reset" class="btn col-sm-3 py-2">
											<?php echo __($this->ResetLabel);?>
										</button>
									<?php endif;
									if(isValid($this->SubmitLabel)):?>
										<button type="submit" id="submit" name="submit" class="btn col-sm btn-main py-2">
											<?php echo __($this->SubmitLabel);?>
										</button>
									<?php endif;?>
								</div>
						</form>
						<?php $this->EchoFooter();?>
					</div>
				</div>
			</div>
			<?php
		endif;
	}
	public function EchoContent($attr = null){
		if($this->QRCodeBox != null){
			$this->QRCodeBox->Content = $this->Content;
			$this->QRCodeBox->Draw();
		}
    }
}
?>