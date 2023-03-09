<?php namespace MiMFa\Module;
class PagePart extends Module{
	//public $Class = "container-fluid";
	public $Image = null;
	public $TitleTag = "h2";
	public $TopSeparatorContent = null;
	public $BottomSeparatorContent = "<hr>";

	public function EchoStyle(){
		parent::EchoStyle();?>
		<style>
			.<?php echo $this->Name; ?>{
				font-size: var(--Size-1);
				text-align: justify;
				padding: 3vmax 3vmax;
			}
			.<?php echo $this->Name; ?> .description{
				font-size: var(--Size-2);
				text-align: justify;
			}
			.<?php echo $this->Name; ?> .image{
				min-height: 5vh;
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
		echo $this->TopSeparatorContent;
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
		echo $this->BottomSeparatorContent;
	}
}
?>