<?php namespace MiMFa\Module;
class Image extends Module{
	public $Source = null;
	public $Image = null;
	public $Tag = null;
	public $AllowOriginal = false;

	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
		.<?php echo $this->Name; ?>{
			min-width: 1vw;
			min-height: 1vh;
			max-width: 100vw;
			max-height: 100vh;
			background-position: center;
			background-repeat: no-repeat;
			background-size: contain;
		}
		</style>
		<?php 
	}

	public function Echo(){
		parent::Echo();
		$src = $this->Source??$this->Image;
		if(isValid($src)) 
			if($this->AllowOriginal)
				if(isFormat($src,".svg")) echo "<embed ".$this->GetDefaultAttributes()." src=\"".$src."\"></embed>";
				else echo "<img ".$this->GetDefaultAttributes()." src=\"$src\"/>";
			else echo "<div ".$this->GetDefaultAttributes()." style=\"background-image: url('$src');\"></div>";
	}
}
?>
