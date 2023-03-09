<?php namespace MiMFa\Module;
class IFrame extends Module{
	public $Name = "IFrame";
	public $Source = null;
	public $Image = null;
	public $AllowFullScreen = "allowfullscreen";
	public $ForeColor = null;
	public $BackColor = "transparent";
	public $BorderColor = "transparent";
	public $Height = "auto";
	public $Width = "100%";
	public $MinHeight = "10px";
	public $MinWidth = "10px";
	public $MaxHeight = "100vh";
	public $MaxWidth = "100vw";
	
	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name; ?>{
				color: <?php echo $this->ForeColor; ?>;
				text-align: center;
			}
			.<?php echo $this->Name; ?> .block{
				background: <?php echo $this->BackColor; ?>88 url('<?php echo $this->Image; ?>') no-repeat center;
				min-width: <?php echo $this->MinWidth; ?>;
				min-height: <?php echo $this->MinHeight; ?>;
				max-width: <?php echo $this->MaxWidth; ?>;
				max-height: <?php echo $this->MaxHeight; ?>;
				height: <?php echo $this->Height; ?>;
				width: <?php echo $this->Width; ?>;
				margin-bottom: 30px;
				border: <?php echo \_::$TEMPLATE->Border(1); ?> transparent;
				border-radius: <?php echo \_::$TEMPLATE->Radius(1); ?>;
			}
			.<?php echo $this->Name; ?> .block:hover{
				background-color: <?php echo $this->BackColor; ?>;
				border-color: <?php echo $this->BackColor; ?>;
			}
		</style>
		<?php
	}

	public function Echo(){
		parent::Echo();
		?>	
			<iframe class="block"
					allowfullscreen="<?php echo $this->AllowFullScreen; ?>"
					src="<?php echo $this->Source; ?>">
			</iframe>
		<?php
	}
}
?>