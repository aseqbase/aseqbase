<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
class Translator extends Module{
	public $Items = array(
			"en"=>array(
				"Title" =>"English",
				"Image" =>"https://flagcdn.com/16x12/gb.png",
				"Direction"=>"ltr",
				"Encoding"=>"UTF-8"
			)
		);
    public $Printable = false;

	public function GetStyle(){
		return parent::GetStyle().Html::Style("
            .{$this->Name} i {
				cursor: pointer;
				padding: 8px;
            }
		");
	}

	public function Get(){
		$cur = \_::$Back->Translate->Language;
		$langs = [];
		foreach ($this->Items??[] as $lng=>$value)
            if($lng != $cur)
                $langs[] = Html::Element(
					Html::Media("", getBetween($value,"Image","Icon")).
					getBetween($value,"Title","Name" ),
					"button",
					["class"=>"button", "onclick"=>"load(\"?".(getBetween($value,"Query" )??"lang=$lng&direction=".get($value,"Direction")."&encoding=".get($value,"Encoding"))."\");"]);
		return $this->GetTitle().$this->GetDescription().
			join(PHP_EOL, $langs).
			$this->GetContent();
    }
}
?>