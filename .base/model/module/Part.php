<?php
namespace MiMFa\Module;
MODULE("Separator");
class Part extends Module{
	public $Image = null;
	public $TitleTag = "h2";

	public function EchoStyle(){
		parent::EchoStyle();?>
		<style>
			.<?php echo $this->Name; ?>{
				font-size: var(--Size-1);
				padding: 3vmax 3vmax;
			}
			.<?php echo $this->Name; ?> .description{
				font-size: var(--Size-2);
			}
			.<?php echo $this->Name; ?> .image{
				min-height: 20vh;
				background-size: contain;
				background-position: center;
				background-repeat: no-repeat;
				font-size: var(--Size-1);
			}
		</style>
		<?php
	}

	public function Echo(){
		$this->EchoTitle();
		?>
		<div class="row">
			<?php
			$this->EchoDescription("class='col-md description'");
			if(isValid($this->Image)){ ?>
				<div class="col-md-4 image" style="background-image: url('<?php echo $this->Image; ?>')"></div>
			<?php } ?>
		</div>
		<?php
		$this->EchoContent("class='content'");
	}
}
?>