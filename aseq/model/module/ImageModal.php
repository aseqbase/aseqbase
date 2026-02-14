<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
use MiMFa\Library\Script;
module("Modal");
class ImageModal extends Modal
{
	public $Image = null;
	public $AllowOrigin = true;

	public function GetStyle()
	{
        yield parent::GetStyle();
        yield Struct::Style("
		.{$this->MainClass} .content .image{
			background-position: center;
			background-repeat: no-repeat;
			background-size: contain;
			width: 100%;
			height: 100%;
		}
		");
	}
	public function GetScript()
	{
		yield parent::GetScript();
		yield Struct::Script("
			function {$this->MainClass}_Set(content, source = null){
				{$this->MainClass}_Source = source??{$this->MainClass}_Source??content;
				if(content !== null) _('.{$this->MainClass}>.content').html(
		" . (
			$this->AllowOrigin ? "content.endsWith(\".svg\")?
				(`<iframe class=\"image\" style=\"height: 100%; width: auto;\" src=\"`+content+`\"></iframe>`):
				(`<div class=\"image\" style=\"background-image: url('`+content+`');\"></div>`)" :
					"`<div class=\"image\" style=\"background-image: url('`+content+`');\"></div>`"
			) . ");
			}"
		);
	}
	public function GetContents($content)
	{
		$content = $content ?? $this->Image;
		if (isValid($content))
			if ($this->AllowOrigin)
				if (isFormat($content, ".svg"))
					return Struct::Embed(null, $content, ["class" => "image", "style" => "height: 100%;width: auto;"]);
				//return parent::GetContents("<iframe class=\"image\" style=\"height: 100%;width: auto;\" src=\"".$content."\"></iframe>");
				else
					return Struct::Media(null, $content, ["class" => "image"]);
			else
				return Struct::Media(null, $content, ["class" => "image"]);
		else
			return parent::GetContents($content ?? $this->Content);
	}

	public function ShowScript($title = null, $description = null, $content = null, $buttonsContent = null, $source = null)
	{
		return $this->MainClass . "_Show(" .
			Script::Convert($title ?? $this->Title) . ", " .
			Script::Convert($description ?? $this->Description) . ", " .
			Script::Convert($this->GetContents($content ?? $this->Content)) . ", " .
			Script::Convert($buttonsContent ?? $this->ButtonsContent) . ", " .
			Script::Convert($source ?? $this->Source) . ");";
	}
}
?>