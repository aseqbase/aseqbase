<?php namespace MiMFa\Module;
MODULE("IFrame");
class EmbededYoutube extends IFrame{
	public $Name = "EmbededYoutube";
	public $Source = null;
	public $Image = null;
	public $AllowFullScreen = "allowfullscreen";
	public $ForeColor = null;
	public $BackColor = "#bb0000";
	public $BorderColor = "#bb0000";
	public $Height = "400px";
	public $Width = "800px";
	public $MaxHeight = "90vmin";
	public $MaxWidth = "100%";

	public function __construct(){
		parent::__construct();
		$this->Image = \MiMFa\Library\Local::GetUrl("file/technology/YouTube.png");
	}
}
?>