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
				<?php echo \MiMFa\Library\Style::DoProperty("color", $this->ForeColor); ?>
				text-align: center;
			}
			.<?php echo $this->Name; ?> .block{
				background: <?php echo $this->BackColor; ?>88 url('<?php echo $this->Image; ?>') no-repeat center;
				<?php echo \MiMFa\Library\Style::DoProperty("min-width",$this->MinWidth); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("min-height", $this->MinHeight); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("max-width", $this->MaxWidth); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("max-height", $this->MaxHeight); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("width", $this->Width); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("height", $this->Height); ?>
				margin-bottom: 30px;
				border: <?php echo \_::$TEMPLATE->Border(1); ?> transparent;
				<?php echo \MiMFa\Library\Style::DoProperty("border", \_::$TEMPLATE->Border(1) . " transparent"); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("border-radius", \_::$TEMPLATE->Radius(1)); ?>
			}
			.<?php echo $this->Name; ?> .block:hover{
				<?php echo \MiMFa\Library\Style::DoProperty("background-color", $this->BackColor); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("border-color", $this->BorderColor); ?>
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