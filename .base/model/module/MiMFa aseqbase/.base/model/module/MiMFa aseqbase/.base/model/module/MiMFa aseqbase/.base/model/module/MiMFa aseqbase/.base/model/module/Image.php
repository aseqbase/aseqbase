<?php namespace MiMFa\Module;
class Image extends Module{
	public $Source = null;
	public $Image = null;
	public $Tag = null;
	public $AllowOriginal = false;

	/**
     * The Width of Image
     * @var string
     */
	public $Width = "inherit";
	/**
     * The Height of thumbnail preshow
     * @var string
     */
	public $Height = "inherit";
	/**
     * The Minimum Width of Image
     * @var string
     */
	public $MinWidth = "1vw";
	/**
     * The Minimum Height of Image
     * @var string
     */
	public $MinHeight = "1vh";
    /**
     * The Maximum Width of Image
     * @var string
     */
	public $MaxWidth = "100vw";
	/**
     * The Maximum Height of thumbnail preshow
     * @var string
     */
	public $MaxHeight = "100vh";

	public function EchoStyle(){
		parent::EchoStyle();
?>
		<style>
		.<?php echo $this->Name; ?>{
			min-width:  <?php echo $this->MinWidth; ?>;
			min-height:  <?php echo $this->MinHeight; ?>;
			max-width:  <?php echo $this->MaxWidth; ?>;
			max-height:  <?php echo $this->MaxHeight; ?>;
			width:  <?php echo $this->Width; ?>;
			height: <?php echo $this->Height; ?>;
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
