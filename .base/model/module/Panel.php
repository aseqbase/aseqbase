<?php
namespace MiMFa\Module;
class Panel extends Module{
	public $ContentTag = null;

	public function Echo(){
		$this->EchoTitle();
		$this->EchoDescription();
		$this->EchoContent();
        return true;
    }
}
?>
