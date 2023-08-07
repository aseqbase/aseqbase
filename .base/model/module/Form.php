<?php namespace MiMFa\Module;
class Form extends Module{
	public $Path = null;
	public $Action = null;
	public $Image = null;
	public $Title = "Reset";
	public $SubmitLabel = "Submit";
	public $ResetLabel = "Reset";
	public $BackLabel = "Back to Home";
	public $BackLink = "/";
	public $Method = "GET";
	public $SuccessPath = null;
	public $ErrorPath = null;

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
		$src = $this->Action??$this->Path??\_::$PATH;
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
							<?php
								$this->EchoContent();
								$this->EchoFields();
							?>
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
	public function EchoHeader(){
        
    }
	public function EchoFields(){
        
    }
	public function EchoFooter(){
        
    }

	public function EchoScript(){
		parent::EchoScript();
		?>
		<script>
			$(function () {
				handleForm(".<?php echo $this->Name ?> form",
					function (data, selector)  {//success
						if (data.includes("result success")) {
							$(`.<?php echo $this->Name ?> .page`).remove();
							$(`.<?php echo $this->Name ?>`).append(data);
							<?php if(isValid($this->SuccessPath)) echo "load(`$this->SuccessPath`);";?>
						}
						else {
							$(`.<?php echo $this->Name ?> form .result`).remove();
							$(`.<?php echo $this->Name ?> form`).append(data);
            							<?php if(isValid($this->ErrorPath)) echo "load(`$this->ErrorPath`);";?>
						}
					}
				);
				$(`.<?php echo $this->Name ?> :is(input, select, textarea)`).on('focus', function () {
					$(this).parent().find(`.<?php echo $this->Name ?> .input-group-text`).css('border-color', 'var(--ForeColor-2)');
				});
				$(`.<?php echo $this->Name ?> :is(input, select, textarea)`).on('blur', function () {
					$(this).parent().find(`.<?php echo $this->Name ?> .input-group-text`).css('border-color', 'var(--ForeColor-2)');
				});
			});
		</script>
		<?php
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
			if(count($_req) > 0)
                echo "<div class='page result success'>".__("The form submitted successfully!")."</div>";
			else echo "<div class='result warning'>".__("There a problem is occured!")."</div>";
		} catch(\Exception $ex) { echo "<div class='result error'>".__($ex->getMessage())."</div>"; }
    }
}
?>