<?php namespace MiMFa\Module;
use \MiMFa\Library\Html;
class FixedScreen extends Module{
	public $Image = null;
	public $BlurSize = "0px";
	
	public function GetStyle(){
		return parent::GetStyle().Html::Style("
			body{
				padding: 0px;
			}
			.{$this->Name}{
				display: flex;
				justify-content: center;
			}
			.{$this->Name} .background{
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

	public function Get(){
		return Html::Division(null,["class"=>"background","style"=>"background-image: url('{$this->Image}');"]).parent::Get();
	}
}
?>