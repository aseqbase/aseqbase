<?php namespace MiMFa\Module;
class FixedScreen extends Module{
	public $Image = null;
	public $BlurSize = "0px";
	
	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
			body{
				padding: 0px;
			}
			.<?php echo $this->Name; ?>{
				display: flex;
				justify-content: center;
			}
			.<?php echo $this->Name; ?> .background{
				height: 100vh;
				width: 100vw;
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover;
				position: fixed;
    			top: 0px;
    			bottom: 0px;
    			left: 0px;
    			right: 0px;
				z-index: -999999999;
				<?php echo \MiMFa\Library\Style::UniversalProperty("filter","blur(".$this->BlurSize.")");?>
			}
		</style>
		<?php
	}

	public function Echo(){
		?>
		<div class="background" style="background-image: url('<?php echo $this->Image;?>');">
		</div>
		<?php
		parent::Echo();
	}
}
?>