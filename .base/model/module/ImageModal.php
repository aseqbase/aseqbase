<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Script;
module("Modal");
class ImageModal extends Modal{
	public $Image = null;
	public $AllowOriginal = true;

	public function GetStyle(){
		return parent::GetStyle().Html::Style("
		.{$this->Name} .content .image{
			background-position: center;
			background-repeat: no-repeat;
			background-size: contain;
			width: 100%;
			height: 100%;
		}
		");
	}

	public function GetContents($content){
		$content = $content??$this->Image;
		if(isValid($content))
			if($this->AllowOriginal)
				if(isFormat($content,".svg")) 
					return Html::Embed(null,$content, ["class"=>"image", "style"=>"height: 100%;width: auto;"]);
					//return parent::GetContents("<iframe class=\"image\" style=\"height: 100%;width: auto;\" src=\"".$content."\"></iframe>");
				else return Html::Media(null,$content, ["class"=>"image"]);
			else return Html::Media(null,$content, ["class"=>"image"]);
		else return parent::GetContents($content??$this->Content);
	}

	public function ShowScript($title = null, $description = null, $content = null, $buttonsContent = null, $source = null){
		return $this->Name."_Show(".
		Script::Convert($title??$this->Title).", ".
		Script::Convert($description??$this->Description).", ".
		Script::Convert($this->GetContents($content??$this->Content)).", ".
		Script::Convert($buttonsContent??$this->ButtonsContent).", ".
		Script::Convert($source??$this->Source).");";
	}
	public function ContentScript($parameterName){
		if($this->AllowOriginal) return "$parameterName.endsWith(\".svg\")?
		(`<iframe class=\"image\" style=\"height: 100%; width: auto;\" src=\"`+$parameterName+`\"></iframe>`):
		(`<div class=\"image\" style=\"background-image: url('`+$parameterName+`');\"></div>`)";
		else return "`<div class=\"image\" style=\"background-image: url('`+$parameterName+`');\"></div>`";
	}
}
?>