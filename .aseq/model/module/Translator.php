<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
class Translator extends Module{
	/**
	 * An array of language metadata
	 * @example: array(
	 *		"en"=>array(
	 *			"Title" =>"English",
	 *			"Image" =>"https://flagcdn.com/16x12/gb.png",
	 *			"Direction"=>"ltr",
	 *			"Encoding"=>"UTF-8"
	 *		)
	 *	)
	 * @var array
	 */
	public $Items = [];
    public $ShowCode = false;
    public $ShowLabel = false;
    public $ShowImage = true;
    public $Printable = false;

	public function GetStyle(){
		return parent::GetStyle().Html::Style("
            .{$this->Name} button {
				cursor: pointer;
				padding: 8px;
				gap: var(--size-0);
				color: inherit;
            }
            .{$this->Name} button .image {
				max-width: var(--size-3);
				text-transform: uppercase;
            }
		");
	}

	public function Get(){
		$cur = \_::$Back->Translate->Language;
		$langs = [];
		foreach ($this->Items??[] as $lng=>$value)
            if($lng != $cur)
                $langs[] = Html::Element(
					($this->ShowCode?strtoupper($lng):"").
					($this->ShowImage?Html::Image($lng, getBetween($value,"Image","Icon"), ["onerror"=>"this.src='".asset("/asset/overlay/glass.png")."';"]):"").
					($this->ShowLabel?getBetween($value,"Title","Name" ):""),
					"button",
					["class"=>"button", "onclick"=>"load(\"?".(getBetween($value,"Query" )??"lang=$lng&direction=".get($value,"Direction")."&encoding=".get($value,"Encoding"))."\");"]);
		return $this->GetTitle().$this->GetDescription().
			join(PHP_EOL, $langs).
			$this->GetContent();
    }
}
?>