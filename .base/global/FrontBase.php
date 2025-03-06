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

	/**
	 * Default page head Packages
	 * @field html
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
	 * @template array [normal, input, buttton, hover, inside, outside]
	 * @var mixed
	 */
	public $ForeColorPalette = array("#151515", "#202020", "#101010", "#040506", "#3aa3e9", "#fdfeff");
	/**
	 * Back Colors Palette
	 * @field array<color>
	 * @template array [normal, input, buttton, hover, inside, outside]
	 * @var mixed
	 */
	public $BackColorPalette = array("#fdfeff", "#fafbfc", "#fdfeff", "#fafcfd", "#fdfeff", "#3aa3e9");
	/**
	 * Fonts Palette
	 * @field array<font>
	 * @template array [normal, input, buttton]
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
	 * @field array<{'size', 'size', 'size', 'color'}>
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

	public static function LoopPalette($palette, int $ind = 0)
	{
		$ind %= count($palette);
		return $palette[$ind];
	}
	public static function LimitPalette($palette, int $ind = 0)
	{
		return $palette[$ind >= count($palette) ? count($palette) - 1 : max(0, $ind)];
	}

	public function Color(int $ind = 0)
	{
		return self::LoopPalette($this->ColorPalette, $ind);
	}
	public function ForeColor(int $ind = 0)
	{
		return self::LoopPalette($this->ForeColorPalette, $ind);
	}
	public function BackColor(int $ind = 0)
	{
		return self::LoopPalette($this->BackColorPalette, $ind);
	}
	public function Font(int $ind = 0)
	{
		return self::LoopPalette($this->FontPalette, $ind);
	}
	public function Size(int $ind = 0)
	{
		return self::LimitPalette($this->SizePalette, $ind);
	}
	public function Shadow(int $ind = 0)
	{
		return self::LimitPalette($this->ShadowPalette, $ind);
	}
	public function Border(int $ind = 0)
	{
		return self::LimitPalette($this->BorderPalette, $ind);
	}
	public function Radius(int $ind = 0)
	{
		return self::LimitPalette($this->RadiusPalette, $ind);
	}
	public function Transition(int $ind = 0)
	{
		return self::LimitPalette($this->TransitionPalette, $ind);
	}
	public function Overlay(int $ind = 0)
	{
		return \MiMFa\Library\Local::GetUrl(self::LoopPalette($this->OverlayPalette, $ind));
	}
	public function Pattern(int $ind = 0)
	{
		return \MiMFa\Library\Local::GetUrl(self::LoopPalette($this->PatternPalette, $ind));
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
		$sc = hexdec(findValid($rgba, 0, 0)) + hexdec(findValid($rgba, 1, 0)) + hexdec(findValid($rgba, 2, 0));
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
	 * Get and Set special part of client side
	 * @param mixed $script The front JS codes
	 * @param mixed $callback The call back handler
	 * @example: Interact('$("body").html', function(selectedHtml)=>{ //do somework })
	 */
	public static function Interact($script = null, $callback = null)
	{
        $selector = "'body'";
        $callbackScript = "(data,err)=>$($selector).append(data??err)";
        $progressScript = "null";
		$timeout = 60000;
		$id = "S_".getID(true);
		if(isStatic($callback)) echo "<script id='$id'>$(document).ready(()=>{(".$callbackScript.")(".
				\MiMFa\Library\Script::Convert($callback).
			",$script);document.getElementById('$id').remove();});</script>";
		else echo "<script id='$id'>" .(
				$callback ? '$(document).ready(()=>{'.
					'sendInternal(null,{"' .
						\MiMFa\Library\Internal::Set($callback) . '":JSON.stringify('. $script .
					")},$selector,$callbackScript,$callbackScript,null,$progressScript,$timeout);document.getElementById('$id').remove();});"
				: $script
			). "</script>";
	}
	/**
	 * Get a special part of client side
	 * @param mixed $selector The source selector
	 * @param mixed $callback The call back handler
	 * @example: Get("body", function(selectedHtml)=>{ //do somework })
	 */
	public static function Get($selector = null, $callback = null)
	{
		self::Interact("$(" . ($selector ? \MiMFa\Library\Script::Convert($selector) : '"body"') . ").prop('outerHTML')", $callback);
	}
	/**
	 * Set output instead if a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public static function Set($selector = null, $handler = null, ...$args)
	{
		echo "<script>" .
			\MiMFa\Library\Internal::MakeScript(
				$handler,
				$args,
				"(data,err)=> $(" . ($selector ? \MiMFa\Library\Script::Convert($selector) : '"body"') . ").replaceWith(data??err)"
			)
			. "</script>";
	}
	/**
	 * Print output inside a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public static function Fill($selector = "body", $handler = null, ...$args)
	{
		echo "<script>" .
			\MiMFa\Library\Internal::MakeScript(
				$handler,
				$args,
				"(data,err)=> $(" . ($selector ? \MiMFa\Library\Script::Convert($selector) : '"body"') . ").html(data??err)"
			)
			. "</script>";
	}
	/**
	 * Append output on a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public static function Append($selector = "body", $handler = null, ...$args)
	{
		echo "<script>" .
			\MiMFa\Library\Internal::MakeScript(
				$handler,
				$args,
				"(data,err)=>$(" . ($selector ? \MiMFa\Library\Script::Convert($selector) : '"body"') . ").append(data??err)"
			)
			. "</script>";
	}
	/**
	 * Prepend output on a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public static function Prepend($selector = "body", $handler = null, ...$args)
	{
		echo "<script>" .
			\MiMFa\Library\Internal::MakeScript(
				$handler,
				$args,
				"(data,err)=>$(" . ($selector ? \MiMFa\Library\Script::Convert($selector) : '"body"') . ").prepend(data??err)"
			)
			. "</script>";
	}
	/**
	 * Forget a special part of client side
	 * @param mixed $selector The destination selector
	 */
	public static function Forget($selector = "body")
	{
		echo "<script>$(" . ($selector ? \MiMFa\Library\Script::Convert($selector) : '"body"') . ").remove()</script>";
	}

	/**
	 * Render Scripts in the client side
	 * @param mixed $output The data that is ready to print
	 */
	public static function Script($content, $source = null, ...$attributes)
	{
		self::Append("head", \MiMFa\Library\Html::Script($content, $source, ...$attributes));
	}
	/**
	 * Render Styles in the client side
	 * @param mixed $output The data that is ready to print
	 */
	public static function Style($content, $source = null, ...$attributes)
	{
		self::Append("head", \MiMFa\Library\Html::Style($content, $source, ...$attributes));
	}

	public static function Alert($message = null, $callback = null)
	{
		self::Interact(
			\MiMFa\Library\Script::Alert($message)."??true",
			$callback
		);
	}
	public static function Confirm($message = null, $callback = null)
	{
		self::Interact(
			\MiMFa\Library\Script::Confirm($message),
			$callback
		);
	}
	public static function Prompt($message = null, $callback = null, $default = null)
	{
		self::Interact(
			\MiMFa\Library\Script::Prompt($message, $default),
			$callback
		);
	}
}
?>