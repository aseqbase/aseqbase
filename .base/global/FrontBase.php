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
	public $AnimationSpeed = 0;
	public $DetectMode = false;
	public $SwitchMode = null;
	public $DefaultMode = null;
	public $CurrentMode = null;
	/**
	 * The website default template class
	 * @var string
	 * @default "Template"
	 * @category General
	 */
	public $DefaultTemplate = "Template";
	/**
	 * The website view name
	 * @var string
	 * @default "main"
	 * @category General
	 */
	public $DefaultViewName = "main";
	public $DefaultSourceSelector = "body";
	public $DefaultDestinationSelector = "body";

	public $SwitchRequest = "SwitchMode";

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
	public $ColorPalette = array("#212529", "#dc3545", "#198754", "#0d6efd", "#ffc107", "#0dcaf0", "#6f42c1", "#f8f9fa");
	/**
	 * Fore Colors Palette
	 * @field array<color>
	 * @template array [normal, inside, outside, special, special-input, special-output]
	 * @var mixed
	 */
	public $ForeColorPalette = array("#151515", "#202020", "#101010", "#040506", "#3aa3e9", "#fdfeff");
	/**
	 * Back Colors Palette
	 * @field array<color>
	 * @template array [normal, inside, outside, special, special-input, special-output]
	 * @var mixed
	 */
	public $BackColorPalette = array("#fdfeff", "#fafbfc", "#fdfeff", "#fafcfd", "#fdfeff", "#3aa3e9");
	/**
	 * Fonts Palette
	 * @field array<font>
	 * @template array [normal, inside, outside]
	 * @var mixed
	 */
	public $FontPalette = array("'Dubai-Light', sans-serif", "'Dubai', sans-serif", "'Dubai', sans-serif");
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
	public $RadiusPalette = array("unset", "3px", "5px", "25px", "50%", "100%");
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

	public function __construct()
	{
		\MiMFa\Library\Revise::Load($this);
		$this->Libraries[] = \MiMFa\Library\Html::Script(null, asset(\_::$Address->ScriptDirectory, 'global.js', optimize: true));
		$this->DefaultMode = $this->CurrentMode = $this->GetMode($this->BackColor(0));
		$this->SwitchMode = getReceived($this->SwitchRequest) ?? getMemo($this->SwitchRequest) ?? $this->SwitchMode;
		if($this->DetectMode && is_null($this->SwitchMode)) {
			request("window.matchMedia('(prefers-color-scheme: dark)').matches ? -1 : 1", 
			function($mode){
				$cmode = \_::$Front->GetMode();
				if(($mode>0 && $cmode < 0) || ($mode<0 && $cmode > 0)){
					setMemo(\_::$Front->SwitchRequest, true);
					\_::$Front->SwitchMode = true;
				}
			});
		}
		if ($this->SwitchMode) {
			$middle = $this->ForeColorPalette;
			$this->ForeColorPalette = $this->BackColorPalette;
			$this->BackColorPalette = $middle;
			$this->CurrentMode = $this->GetMode($this->BackColor(0));
		}
	}

	public function CreateTemplate($name = null, $data = [])
	{
		return new (template($name, $data, alternative: $this->DefaultTemplate))();
	}

	/**
	 * Get the lightness of a color with a number between -255 to +255
	 * @param mixed $color A three, four, six or eight characters hexadecimal color numbers for example #f80 or #ff8800
	 * @return float|int A number between -255 (for maximum in darkness) to +255 (for maximum in lightness)
	 */
	public function GetMode($color = null)
	{
		if (!isValid($color))
			if (!is_null($this->CurrentMode))
				return $this->CurrentMode;
			else
				return $this->GetMode($this->BackColor(0));
		$l = strlen($color) > 6;
		$rgb = preg_find_all($l ? '/\w\w/' : '/\w/', $color);
		$sc = ($l ? hexdec(getValid($rgb, 0, 0)) + hexdec(getValid($rgb, 1, 0)) + hexdec(getValid($rgb, 2, 0)) :
			hexdec(getValid($rgb, 0, 0)) * 16 + hexdec(getValid($rgb, 1, 0)) * 16 + hexdec(getValid($rgb, 2, 0)) * 16 - 3);
		return $sc > 510 ? $sc - 510 : ($sc < 255 ? $sc - 255 : $sc - 382.5);
	}

	
	public function LoopPalette($palette, int $index = 0)
	{
		$index %= count($palette);
		return $palette[$index];
	}
	public function LimitPalette($palette, int $index = 0)
	{
		return $palette[$index >= count($palette) ? count($palette) - 1 : max(0, $index)];
	}

	/**
	 * To get the Color by index
	 * @param int $index 0:Black, 1:Red, 2:Green, 3:Blue, 4:Yellow, 5:Cyan, 6:Violet, 7:White
	 */
	public function Color(int $index = 0)
	{
		return $this->LoopPalette($this->ColorPalette, $index);
	}
	/**
	 * To get the ForeColor by index
	 * @param int $index 0:normal, 1:inside, 2:outside, 3:special, 4:special-input, 5:special-output
	 */
	public function ForeColor(int $index = 0)
	{
		return $this->LoopPalette($this->ForeColorPalette, $index);
	}
	/**
	 * To get the BackColor by index
	 * @param int $index 0:normal, 1:inside, 2:outside, 3:special, 4:special-input, 5:special-output
	 */
	public function BackColor(int $index = 0)
	{
		return $this->LoopPalette($this->BackColorPalette, $index);
	}
	/**
	 * To get the Font by index
	 * @param int $index 0:normal, 1:inside, 2:outside
	 */
	public function Font(int $index = 0)
	{
		return $this->LoopPalette($this->FontPalette, $index);
	}
	/**
	 * To get the Size by index
	 * @param int $index 0:sm, 1:n, 2:lg, 3:xlg, 4:xxlg,...
	 */
	public function Size(int $index = 0)
	{
		return $this->LimitPalette($this->SizePalette, $index);
	}
	/**
	 * To get the Shadow by index
	 * @param int $index 0:minimum, 1:normal, 2:medium, 3:maximum,...
	 */
	public function Shadow(int $index = 0)
	{
		return $this->LimitPalette($this->ShadowPalette, $index);
	}
	/**
	 * To get the Border size by index
	 * @param int $index 0:minimum, 1:normal, 2:medium, 3:maximum,...
	 */
	public function Border(int $index = 0)
	{
		return $this->LimitPalette($this->BorderPalette, $index);
	}
	/**
	 * To get the Radius size by index
	 * @param int $index 0:minimum, 1:normal, 2:medium, 3:maximum,...
	 */
	public function Radius(int $index = 0)
	{
		return $this->LimitPalette($this->RadiusPalette, $index);
	}
	/**
	 * To get the Transition by index
	 * @param int $index 0:minimum, 1:normal, 2:medium, 3:maximum,...
	 */
	public function Transition(int $index = 0)
	{
		return $this->LimitPalette($this->TransitionPalette, $index);
	}
	/**
	 * To get the Overlay image by index
	 * @param int $index 0:minimum, 1:normal, 2:medium, 3:maximum,...
	 */
	public function Overlay(int $index = 0)
	{
		return \MiMFa\Library\Local::GetUrl($this->LoopPalette($this->OverlayPalette, $index));
	}
	/**
	 * To get the Pattern image by index
	 * @param int $index 0:minimum, 1:normal, 2:medium, 3:maximum,...
	 */
	public function Pattern(int $index = 0)
	{
		return \MiMFa\Library\Local::GetUrl($this->LoopPalette($this->PatternPalette, $index));
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
	 * @example: get("body", function(selectedHtml)=>{ //do somework })
	 */
	public static function Bring($selector = null, $callback = null)
	{
		bring("document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector ?? 'body') . ")", $callback);
	}

	/**
	 * Get all specific parts of the client side
	 * @param mixed $selector The source selector
	 * @param mixed $callback The call back handler
	 * @example: get("body", function(selectedHtml)=>{ //do somework })
	 */
	public function Get($selector = null, $callback = null)
	{
		request("Array.from(document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector ?? $this->DefaultSourceSelector) . ").values().map(el=>el.outerHTML))", $callback);
	}
	/**
	 * Replace the output with a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function Set($selector = null, $handler = null, ...$args)
	{
		return injectScript($this->MakeSetScript($selector, $handler, ...$args));
	}
	/**
	 * Make a script to
	 * Replace the output with a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function MakeSetScript($selector = null, $handler = null, ...$args)
	{
		return \MiMFa\Library\Internal::MakeScript(
			$handler,
			$args,
			"(data,err)=>document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector ?? $this->DefaultDestinationSelector) . ").forEach(l=>{l.before(...((html)=>{el=document.createElement('qb');el.innerHTML=html;el.querySelectorAll('script').forEach(script => eval(script.textContent));return el.childNodes;})(data??err));l.remove();})"
		);
	}
	/**
	 * Delete a special part of client side
	 * @param mixed $selector The destination selector
	 */
	public function Delete($selector = "body")
	{
		return injectScript($this->MakeDeleteScript($selector));
	}
	/**
	 * Make a script to Delete a special part of client side
	 * @param mixed $selector The destination selector
	 */
	public function MakeDeleteScript($selector = "body")
	{
		return \MiMFa\Library\Internal::MakeStartScript() . "document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector ?? $this->DefaultSourceSelector) . ").forEach(el=>el.remove())" . \MiMFa\Library\Internal::MakeEndScript();
	}
	/**
	 * Insert output before a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function Before($selector = "body", $handler = null, ...$args)
	{
		return injectScript($this->MakeBeforeScript($selector, $handler, ...$args));
	}
	/**
	 * Make a script to
	 * Insert output before a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function MakeBeforeScript($selector = "body", $handler = null, ...$args)
	{
		return \MiMFa\Library\Internal::MakeScript(
			$handler,
			$args,
			"(data,err)=>document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector ?? $this->DefaultDestinationSelector) . ").forEach(l=>l.before(...((html)=>{el=document.createElement('qb');el.innerHTML=html;el.querySelectorAll('script').forEach(script => eval(script.textContent));return el.childNodes;})(data??err)))"
		);
	}
	/**
	 * Insert output after a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function After($selector = "body", $handler = null, ...$args)
	{
		return injectScript($this->MakeAfterScript($selector, $handler, ...$args));
	}
	/**
	 * Make a script to
	 * Insert output after a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function MakeAfterScript($selector = "body", $handler = null, ...$args)
	{
		return \MiMFa\Library\Internal::MakeScript(
			$handler,
			$args,
			"(data,err)=>document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector ?? $this->DefaultDestinationSelector) . ").forEach(l=>l.after(...((html)=>{el=document.createElement('qb');el.innerHTML=html;el.querySelectorAll('script').forEach(script => eval(script.textContent));return el.childNodes;})(data??err)))"
		);
	}
	/**
	 * Print output inside a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function Fill($selector = "body", $handler = null, ...$args)
	{
		return injectScript($this->MakeFillScript($selector, $handler, ...$args));
	}
	/**
	 * Make a script to
	 * Print output inside a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function MakeFillScript($selector = "body", $handler = null, ...$args)
	{
		return \MiMFa\Library\Internal::MakeScript(
			$handler,
			$args,
			//"(data,err)=>document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector ?? $this->DefaultDestinationSelector) . ").forEach(l=>l.replaceChildren(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err)))"
			"(data,err)=>document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector ?? $this->DefaultDestinationSelector) . ").forEach(l=>{l.replaceChildren(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err));l.querySelectorAll('script').forEach(script => eval(script.textContent));})"
		);
	}
	/**
	 * Prepend output on a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function Prepend($selector = "body", $handler = null, ...$args)
	{
		return injectScript($this->MakePrependScript($selector, $handler, ...$args));
	}
	/**
	 * Make a script to
	 * Prepend output on a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function MakePrependScript($selector = "body", $handler = null, ...$args)
	{
		return \MiMFa\Library\Internal::MakeScript(
			$handler,
			$args,
			"(data,err)=>document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector ?? $this->DefaultDestinationSelector) . ").forEach(l=>{l.prepend(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err));l.querySelectorAll('script').forEach(script => eval(script.textContent));})"
		);
	}
	/**
	 * Append output on a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function Append($selector = "body", $handler = null, ...$args)
	{
		return injectScript($this->MakeAppendScript($selector, $handler, ...$args));
	}
	/**
	 * Make a script to
	 * Append output on a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function MakeAppendScript($selector = "body", $handler = null, ...$args)
	{
		return \MiMFa\Library\Internal::MakeScript(
			$handler,
			$args,
			"(data,err)=>document.querySelectorAll(" . \MiMFa\Library\Script::Convert($selector ?? $this->DefaultDestinationSelector) . ").forEach(l=>{l.append(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err));l.querySelectorAll('script').forEach(script => eval(script.textContent));})"
		);
	}
}