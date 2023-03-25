<?php namespace MiMFa\Module;
class PrePage extends Module{
	public $Class = "container";
	public $Image = null;
	public $TitleTag = "h1";

	public function EchoStyle(){
		parent::EchoStyle();?>
		<style>
			.<?php echo $this->Name; ?> .description{
				font-size: <?php echo \_::$TEMPLATE->Size(1); ?>;
				text-align: justify;
				padding: 3vmax 3vmax;
			}
			.<?php echo $this->Name; ?> .image{
				background-size: cover;
				background-position: center;
				background-repeat: no-repeat;
				font-size: <?php echo \_::$TEMPLATE->Size(1); ?>;
			}
		</style>
		<?php
	}

	public function Echo(){
		$this->EchoTitle();
		?>
		<div class="row">
			<?php $this->EchoDescription("class='col-md description'");
			if(isValid($this->Image)){ ?>
				<div class="blackwhite col-md-4 image" style="background-image: url('<?php echo $this->Image; ?>')"></div>
			<?php } ?>
		</div>
		<?php 
		$this->EchoContent("class='content'");
	}
}
?>