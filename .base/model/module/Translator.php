<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Session;
use MiMFa\Library\Translate;
class Translator extends Module{
	public $Items = array(
			"EN"=>array(
				"Title" =>"English",
				"Image" =>"https://flagcdn.com/16x12/gb.png",
				"Direction"=>"LTR",
				"Encoding"=>"UTF-8"
			)
		);

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
		foreach ($this->Items as $lng=>$value)
            if($lng != $cur)
                $langs[] = Html::Button(
					Html::Media("", getBetween($value,"Image","Icon")).
					getBetween($value,"Title","Name" ),
					"load(\"?lang=$lng&direction=".get($value,"Direction")."&encoding=".get($value,"Encoding")."\");");
		return $this->GetTitle().$this->GetDescription().
			join(PHP_EOL, $langs).
			$this->GetContent();
    }
}
?>