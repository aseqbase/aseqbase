<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
use MiMFa\Library\Session;
use MiMFa\Library\Translate;
class Translator extends Module{
	public $Capturable = true;
	public $Items = array(
			"EN"=>array(
				"Title"=>"English",
				"Image"=>"https://flagcdn.com/16x12/gb.png",
				"Direction"=>"LTR",
				"Encoding"=>"UTF-8"
			)
		);

	public function GetStyle(){
		return parent::GetStyle().HTML::Style("
            .{$this->Name} i {
				cursor: pointer;
				padding: 8px;
            }
		");
	}

	public function Get(){
		$cur = Translate::$Language;
		$langs = [];
		foreach ($this->Items as $lng=>$value)
            if($lng != $cur)
                $langs[] = HTML::Button(
					HTML::Media("", getValid($value,"Image")??getValid($value,"Icon")).
					(getValid($value,"Title")??getValid($value,"Name")),
					"load(\"?lang=$lng&direction=".getValid($value,"Direction")."&encoding=".getValid($value,"Encoding")."\");");
		return $this->GetTitle().$this->GetDescription().
			join(PHP_EOL, $langs).
			$this->GetContent();
    }
}
?>