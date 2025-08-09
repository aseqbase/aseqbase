<?php namespace MiMFa\Module;
module("MediaFrame");
class EmbededYoutube extends MediaFrame{
	public function __construct($source=null){
		parent::__construct($source,"#bb0000", asset("/asset/technology/YouTube.png"),"YouTube");
	}
}
?>