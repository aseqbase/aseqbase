<?php namespace MiMFa\Module;
MODULE("IFrame");
class MediaFrame extends IFrame{
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

	public function __construct($source=null, $color="#bb0000", $logo=null, $name="Media"){
		parent::__construct();
		$this->Source = $source;
		$this->BackColor =
		$this->BorderColor = $color;
		$this->Image = \MiMFa\Library\Local::GetUrl($logo??"file/technology/$name.png");
	}
}
?>