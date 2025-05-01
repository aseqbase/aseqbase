<?php

library("Revise");
/**
 *All the basic website front-end settings and functions
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Structures See the Structures Documentation
 */
abstract class FrontBase
{
	public $AnimationSpeed = 250;
	public $DetectMode = true;
	public $DarkMode = null;
	public $DefaultSourceSelector = "body";
	public $DefaultDestinationSelector = "body";

	/**
	 * Default page head Packages
	 * @field html
	 * @internal
	 * @var array
	 */
	public $Libraries = [];
	/**
	 * The custom head packages
	 * @field html
	 * @var array
	 */
	public $Initials = [];
	/**
	 * The custom top of body's tags
	 * @field html
	 * @var array
	 */
	public $Mains = [];
	/**
	 * The custom top of body's tags
	 * @field html
	 * @var array
	 */
	public $Finals = [];

	/**
	 * Full Colors Palette
	 * @field array<color>
	 * @template array [Black, Red, Green, Blue, Yellow, Cyan, Violet, White]
	 * @var mixed
	 */
	public $ColorPalette = array("#030405", "#dd2222", "#22dd22", "#2222dd", "#ccbb22", "#22dddd", "#dd22dd", "#fdfeff");
	/**
	 * Fore Colors Palette
	 * @field array<color>
	 * @template array [normal, inside, outside, special, special-inside, special-outside]
	 * @var mixed
	 */
	public $ForeColorPalette = array("#151515", "#202020", "#101010", "#040506", "#3aa3e9", "#fdfeff");
	/**
	 * Back Colors Palette
	 * @field array<color>
	 * @template array [normal, inside, outside, special, special-inside, special-outside]
	 * @var mixed
	 */
	public $BackColorPalette = array("#fdfeff", "#fafbfc", "#fdfeff", "#fafcfd", "#fdfeff", "#3aa3e9");
	/**
	 * Fonts Palette
	 * @field array<font>
	 * @template array [normal, inside, outside]
	 * @var mixed
	 */
	public $FontPalette = array("'dubai light', sans-serif", "'dubai', sans-serif", "'dubai', sans-serif", "'Tahoma', sans-serif", "'Tahoma', sans-serif", "'Times new Romance', sans-serif");
	/**
	 * Sizes Palette
	 * @field array<size>
	 * @template array [sm, n, lg, xlg, xxlg,...]
	 * @var mixed
	 */
	public $SizePalette = array("2.3vh", "2.4vh", "2.6vh", "3vh", "3.6vh", "4.4vh", "5.4vh");
	/**
	 * Shadows Palette
	 * @field array<{'size' 'size' 'size' 'color'}>
	 * @field array<text>
	 * @template array [minimum, normal, medium, maximum, ...]
	 * @var mixed
	 */
	public $ShadowPalette = array("none", "4px 7px 20px #00000005", "4px 7px 20px #00000015", "4px 7px 20px #00000030", "5px 10px 25px #00000030", "5px 10px 25px #00000050", "5px 10px 50px #00000050");
	/**
	 * Borders Palette
	 * @field array<{'size', ['solid','double','dotted','dashed']}>
	 * @field array<text>
	 * @template array [minimum, normal, medium, maximum, ...]
	 * @var mixed
	 */
	public $BorderPalette = array("0px", "1px solid", "2px solid", "5px solid", "10px solid", "25px solid");
	/**
	 * Radiuses Palette
	 * @field array<size>
	 * @template array [minimum, normal, medium, maximum, ...]
	 * @var mixed
	 */
	public $RadiusPalette = array("0px", "3px", "5px", "50px", "50%", "100%");
	/**
	 * Transitions Palette
	 * @field array<text>
	 * @template array [minimum, normal, medium, maximum, ...]
	 * @var mixed
	 */
	public $TransitionPalette = array("none", "all .25s linear", "all .5s linear", "all .75s linear", "all 1s linear", "all 1.5s linear");
	/**
	 * Overlays Palette
	 * @field array<path>
	 * @template array [minimum, normal, medium, maximum, ...]
	 * @var mixed
	 */
	public $OverlayPalette = array("/asset/overlay/glass.png", "/asset/overlay/cotton.png", "/asset/overlay/cloud.png", "/asset/overlay/wings.svg", "/asset/overlay/sands.png", "/asset/overlay/dirty.png");
	/**
	 * Patterns Palette
	 * @field array<path>
	 * @template array [minimum, normal, medium, maximum, ...]
	 * @var mixed
	 */
	public $PatternPalette = array("/asset/pattern/main.svg", "/asset/pattern/doddle.png", "/asset/pattern/doddle-fantasy.png", "/asset/pattern/triangle.png", "/asset/pattern/slicksline.png", "/asset/pattern/doddle-mess.png");

	public function LoopPalette($palette, int $index = 0)
	{
		$index %= count($palette);
		return $palette[$index];
	}
	public function LimitPalette($palette, int $index = 0)
	{
		return $palette[$index >= count($palette) ? count($palette) - 1 : max(0, $index)];
	}

	public function Color(int $index = 0)
	{
		return $this->LoopPalette($this->ColorPalette, $index);
	}
	public function ForeColor(int $index = 0)
	{
		return $this->LoopPalette($this->ForeColorPalette, $index);
	}
	public function BackColor(int $index = 0)
	{
		return $this->LoopPalette($this->BackColorPalette, $index);
	}
	public function Font(int $index = 0)
	{
		return $this->LoopPalette($this->FontPalette, $index);
	}
	public function Size(int $index = 0)
	{
		return $this->LimitPalette($this->SizePalette, $index);
	}
	public function Shadow(int $index = 0)
	{
		return $this->LimitPalette($this->ShadowPalette, $index);
	}
	public function Border(int $index = 0)
	{
		return $this->LimitPalette($this->BorderPalette, $index);
	}
	public function Radius(int $index = 0)
	{
		return $this->LimitPalette($this->RadiusPalette, $index);
	}
	public function Transition(int $index = 0)
	{
		return $this->LimitPalette($this->TransitionPalette, $index);
	}
	public function Overlay(int $index = 0)
	{
		return \MiMFa\Library\Local::GetUrl($this->LoopPalette($this->OverlayPalette, $index));
	}
	public function Pattern(int $index = 0)
	{
		return \MiMFa\Library\Local::GetUrl($this->LoopPalette($this->PatternPalette, $index));
	}

	public function __construct()
	{
		\MiMFa\Library\Revise::Load($this);
		if ($this->IsDark($this->BackColor(0)) === true)
			$this->DarkMode = true;
		else
			$this->DarkMode = false;
		$lm = \Req::Receive("LightMode");
		$lm = $lm ? setMemo("LightMode", $lm) : false;
		$dm = \Req::Receive("DarkMode");
		$dm = $dm ? setMemo("DarkMode", $dm) : false;
		if (
			$this->DetectMode && (
				($this->DarkMode && ($lm || getMemo("LightMode")))
				||
				(!$this->DarkMode && ($dm || getMemo("DarkMode")))
			)
		) {
			$middle = $this->ForeColorPalette;
			$this->ForeColorPalette = $this->BackColorPalette;
			$this->BackColorPalette = $middle;
			$this->DarkMode = !$this->DarkMode;
		}
	}

	public function CreateTemplate($name = null, $data = [])
	{
		switch (strtolower($name)) {
			case 'Message':
				template($name, $data);
				return new \MiMFa\Template\Message();
			case 'Splash':
				template($name, $data);
				return new \MiMFa\Template\Splash();
			case 'General':
				template($name, $data);
				return new \MiMFa\Template\General();
			default:
				template("Main", $data);
				return new \MiMFa\Template\Main();
		}
	}

	public function IsDark($color = null): bool|null
	{
		if (!isValid($color))
			return $this->IsDark($this->BackColor(0)) === false;
		$l = strlen($color);
		$rgba = preg_find_all($l > 6 ? '/\w\w/' : '/\w/', $color);
		$sc = hexdec(getValid($rgba, 0, 0)) + hexdec(getValid($rgba, 1, 0)) + hexdec(getValid($rgba, 2, 0));
		if ($sc < 127)
			return true;
		elseif ($sc > 382)
			return false;
		return null;
	}


	public function GetInitial(): string|null
	{
		return join(PHP_EOL, $this->Initials);
	}
	public function GetMain(): string|null
	{
		return join(PHP_EOL, $this->Mains);
	}
	public function GetFinal(): string|null
	{
		return join(PHP_EOL, $this->Finals);
	}

	
	/**
	 * Interact with all specific parts of the client side one by one
	 * @param mixed $selector The source selector
	 * @param mixed $callback The call back handler
	 * @example: Get("body", function(selectedHtml)=>{ //do somework })
	 */
	public static function Iterate($selector = null, $callback = null)
	{
		\Res::Iterate("document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector??'body') . ")", $callback);
	}

	/**
	 * Get all specific parts of the client side
	 * @param mixed $selector The source selector
	 * @param mixed $callback The call back handler
	 * @example: Get("body", function(selectedHtml)=>{ //do somework })
	 */
	public function Get($selector = null, $callback = null)
	{
		\Res::Interact("Array.from(document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector??$this->DefaultSourceSelector) . ").values().map(el=>el.outerHTML))", $callback);
	}
	/**
	 * Replace the output with a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function Set($selector = null, $handler = null, ...$args)
	{
		\Res::Render(\MiMFa\Library\Html::Script(
			\MiMFa\Library\Internal::MakeScript(
				$handler,
				$args,
				"(data,err)=>document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector??$this->DefaultDestinationSelector) . ").forEach(l=>{l.before(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err));l.remove();})"
			)
		));
	}
	/**
	 * Delete a special part of client side
	 * @param mixed $selector The destination selector
	 */
	public function Delete($selector = "body")
	{
		\Res::Render(\MiMFa\Library\Html::Script(\MiMFa\Library\Internal::MakeStartScript()."document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector??$this->DefaultSourceSelector) . ").forEach(el=>el.remove())".\MiMFa\Library\Internal::MakeEndScript()));
	}
	/**
	 * Insert output before a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function Before($selector = "body", $handler = null, ...$args)
	{
		\Res::Render(\MiMFa\Library\Html::Script(
			\MiMFa\Library\Internal::MakeScript(
				$handler,
				$args,
				"(data,err)=>document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector??$this->DefaultDestinationSelector) . ").forEach(l=>l.before(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err)))"
			)
		));
	}
	/**
	 * Insert output after a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function After($selector = "body", $handler = null, ...$args)
	{
		\Res::Render(\MiMFa\Library\Html::Script(
			\MiMFa\Library\Internal::MakeScript(
				$handler,
				$args,
				"(data,err)=>document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector??$this->DefaultDestinationSelector) . ").forEach(l=>l.after(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err)))"
			)
		));
	}
	/**
	 * Print output inside a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function Fill($selector = "body", $handler = null, ...$args)
	{
		\Res::Render(\MiMFa\Library\Html::Script(
			\MiMFa\Library\Internal::MakeScript(
				$handler,
				$args,
				"(data,err)=>document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector??$this->DefaultDestinationSelector) . ").forEach(l=>l.replaceChildren(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err)))"
			)
		));
	}
	/**
	 * Prepend output on a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function Prepend($selector = "body", $handler = null, ...$args)
	{
		\Res::Render(\MiMFa\Library\Html::Script(
			\MiMFa\Library\Internal::MakeScript(
				$handler,
				$args,
				"(data,err)=>document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector??$this->DefaultDestinationSelector) . ").forEach(l=>l.prepend(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err)))"
			)
		));
	}
	/**
	 * Append output on a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function Append($selector = "body", $handler = null, ...$args)
	{
		\Res::Render(\MiMFa\Library\Html::Script(
			\MiMFa\Library\Internal::MakeScript(
				$handler,
				$args,
				"(data,err)=>document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector??$this->DefaultDestinationSelector) . ").forEach(l=>l.append(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err)))"
			)
		));
	}
}
?>