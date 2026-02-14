<?php namespace MiMFa\Module;
use \MiMFa\Library\Struct;
class FixedScreen extends Module{
	public $Image = null;
	public $BlurSize = "0px";
    public $Printable = false;
	
	public function GetStyle(){
        yield parent::GetStyle();
        yield Struct::Style("
			body{
				padding: 0px;
			}
			.{$this->MainClass}{
				display: flex;
				justify-content: center;
			}
			.{$this->MainClass} .background{
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
				".\MiMFa\Library\Style::UniversalProperty("filter","blur(".$this->BlurSize.")")."
			}
		");
	}

	public function GetInner(){
		return Struct::Division(null,["class"=>"background","style"=>"background-image: url('{$this->Image}');"]).parent::GetInner();
	}
}
?>