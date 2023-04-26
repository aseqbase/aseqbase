<?php
namespace MiMFa\Module;
class Separator extends Module{
	public $Image = null;
	public $Width = "100%";
	public $Height = "100px";
	public $MergeTop = false;
	public $MergeBottom = false;
	public $MergeRight = false;
	public $MergeLeft = false;

	public function EchoStyle(){
		parent::EchoStyle();?>
		<style>
			.<?php echo $this->Name; ?>{
				<?php echo isValid($this->Image)?"background-image: url('".$this->Image."');
				background-size: cover;
				background-position: center;
				background-repeat: no-repeat;":null; ?>
				min-height: <?php echo $this->Height; ?>;
				min-width: <?php echo $this->Width; ?>;
				<?php echo $this->MergeTop?"margin-top: calc(".$this->Height."/-2);":null; ?>
				<?php echo $this->MergeBottom?"margin-bottom: calc(".$this->Height."/-2);":null; ?>
				<?php echo $this->MergeRight?"margin-right: calc(".$this->Width."/-2);":null; ?>
				<?php echo $this->MergeLeft?"margin-left: calc(".$this->Width."/-2);":null; ?>
				position: relative;
				display: grid;
				align-items: center;
				text-align: center;
			}
		</style>
		<?php
	}
}
?>