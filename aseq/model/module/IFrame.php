<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
class IFrame extends Module{
	public $Name = "IFrame";
	public $Source = null;
	public $Image = null;
	public $AllowFullScreen = "allowfullscreen";
	public $ForeColor = null;
	public $BackColor = "transparent";
	public $BorderColor = "transparent";
	public $Height = "auto";
	public $Width = "100%";
	public $MinHeight = "10px";
	public $MinWidth = "10px";
	public $MaxHeight = "100vh";
	public $MaxWidth = "100vw";

	/**
     * Create the module
     * @param string|null $source The module source
     */
	public function __construct($source =  null){
        parent::__construct();
		$this->Set($source);
    }
	/**
     * Set the main properties of module
     * @param string|null $source The module source
	 */
	public function Set($source =  null){
		$this->Source = $source;
		return $this;
    }

	public function GetStyle(){
		return parent::GetStyle().Struct::Style("
			.{$this->Name}{
				".\MiMFa\Library\Style::DoProperty("color", $this->ForeColor)."
				text-align: center;
			}
			.{$this->Name} .block{
				background: ".$this->BackColor."88 url('".$this->Image."') no-repeat center;
				background-size: clamp(50px, 100%, 100%);
				".\MiMFa\Library\Style::DoProperty("min-width",$this->MinWidth)."
				".\MiMFa\Library\Style::DoProperty("min-height", $this->MinHeight)."
				".\MiMFa\Library\Style::DoProperty("max-width", $this->MaxWidth)."
				".\MiMFa\Library\Style::DoProperty("max-height", $this->MaxHeight)."
				".\MiMFa\Library\Style::DoProperty("width", $this->Width)."
				".\MiMFa\Library\Style::DoProperty("height", $this->Height)."
				margin-bottom: 30px;
				border: var(--border-1) transparent;
				".\MiMFa\Library\Style::DoProperty("border", "var(--border-1)" . " transparent")."
				".\MiMFa\Library\Style::DoProperty("border-radius", "var(--radius-1)")."
			}
			.{$this->Name} .block:hover{
				".\MiMFa\Library\Style::DoProperty("background-color", $this->BackColor)."
				".\MiMFa\Library\Style::DoProperty("border-color", $this->BorderColor)."
			}");
	}

	public function Get(){
		return parent::Get().Struct::Embed(null,$this->Source, ["class"=>"block","allowfullscreen"=>$this->AllowFullScreen]);
	}
}
?>