<?php namespace MiMFa\Module;
MODULE("MediaFrame");
class EmbededYoutube extends MediaFrame{
	public function __construct($source=null){
		parent::__construct($source,"#bb0000", "asset/technology/YouTube.png","YouTube");
	}
}
?>