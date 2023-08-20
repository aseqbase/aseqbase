<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
MODULE("Modal");
class ImageModal extends Modal{
	public $Capturable = true;
	public $Image = null;
	public $AllowOriginal = true;

	public function GetStyle(){
		return parent::GetStyle().HTML::Style("
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
				if(isFormat($content,".svg")) return parent::GetContents("<iframe class=\"image\" style=\"height: 100%;width: auto;\" src=\"".$content."\"></iframe>");
				else return parent::GetContents("<div class=\"image\" style=\"background-image: url('".$content."');\"/>");
			else return parent::GetContents("<div class=\"image\" style=\"background-image: url('".$content."');\"/>");
		else return parent::GetContents($content??$this->Content);
	}

	public function ContentScript($parameterName){
		if($this->AllowOriginal) return "$parameterName.endsWith(\".svg\")?
		(`<iframe class=\"image\" style=\"height: 100%; width: auto;\" src=\"`+$parameterName+`\"></iframe>`):
		(`<div class=\"image\" style=\"background-image: url('`+$parameterName+`');\"/>`)";
		else return "`<div class=\"image\" style=\"background-image: url('`+$parameterName+`');\"/>`";
	}
}
?>