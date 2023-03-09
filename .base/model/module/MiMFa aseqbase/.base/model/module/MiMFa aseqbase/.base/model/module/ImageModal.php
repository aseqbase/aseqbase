<?php namespace MiMFa\Module;
MODULE("Modal");
class ImageModal extends Modal{
	public $Image = null;
	public $AllowOriginal = true;

	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
		.<?php echo $this->Name; ?> .content .image{
			background-position: center;
			background-repeat: no-repeat;
			background-size: contain;
			width: 100%;
			height: 100%;
		}
		</style>
		<?php
	}

	public function GetContent($content){
		$content = $content??$this->Image;
		if(isValid($content))
			if($this->AllowOriginal)
				if(isFormat($content,".svg")) return parent::GetContent("<iframe class=\"image\" style=\"height: 100%;width: auto;\" src=\"".$content."\"></iframe>");
				else return parent::GetContent("<div class=\"image\" style=\"background-image: url('".$content."');\"/>");
			else return parent::GetContent("<div class=\"image\" style=\"background-image: url('".$content."');\"/>");
		else return parent::GetContent($content??$this->Content);
	}
	
	public function ContentScript($parameterName){
		if($this->AllowOriginal) return "$parameterName.endsWith(\".svg\")?
		(`<iframe class=\"image\" style=\"height: 100%; width: auto;\" src=\"`+$parameterName+`\"></iframe>`):
		(`<div class=\"image\" style=\"background-image: url('`+$parameterName+`');\"/>`)";
		else return "`<div class=\"image\" style=\"background-image: url('`+$parameterName+`');\"/>`";
	}
}
?>