<?php

use MiMFa\Library\Convert;
use MiMFa\Library\Struct;
use MiMFa\Library\Internal;
use MiMFa\Library\Local;
use MiMFa\Library\Revise;
use MiMFa\Library\Script;

library("Revise");
library("Translate");
/**
 *All the basic website front-end settings and functions
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Structures See the Structures Documentation
 */
abstract class FrontBase
{
	public $Items = [];

	/**
	 * The website owner name
	 * @var mixed
	 */
	public $Owner = null;
	/**
	 * The website owner full name
	 * @var mixed
	 */
	public $FullOwner = null;
	/**
	 * Descriptions about the website owner
	 * @field strings
	 * @var mixed
	 */
	public $OwnerDescription = null;
	/**
	 * A full version of descriptions about the website owner
	 * @field strings
	 * @var mixed
	 */
	public $FullOwnerDescription = null;
	/**
	 * The website owner and name
	 * @var mixed
	 */
	public $Name = null;
	/**
	 * The website full owner and full name
	 * @var mixed
	 */
	public $FullName = null;
	/**
	 * The short slogan of the website
	 * @var mixed
	 */
	public $Slogan = null;
	/**
	 * The more detailed slogan of the website
	 * @field strings
	 * @var mixed
	 */
	public $FullSlogan = null;
	/**
	 * The short description of the website
	 * @var mixed
	 */
	public $Description = null;
	/**
	 * The more detailed description of the website
	 * @field strings
	 * @var mixed
	 */
	public $FullDescription = null;

	/**
	 * Default mail sender
	 * @example: "do-not-reply@mimfa.net"
	 * @field array
	 * @var string|null|array<string>
	 * @category Security
	 */
	public $SenderEmail = null;
	/**
	 * Default mail reciever
	 * @example: "info@mimfa.net"
	 * @field array
	 * @var string|null|array<string>
	 * @category Security
	 */
	public $ReceiverEmail = null;
	/**
	 * The main path
	 * @field path
	 * @var mixed
	 */
	public $Path = null;
	/**
	 * The website main logo path
	 * @field path
	 * @var mixed
	 */
	public $LogoPath = "asset/logo/logo.png";
	/**
	 * The website brand logo path
	 * @field path
	 * @var mixed
	 */
	public $BrandLogoPath = "asset/logo/brand-logo.png";
	/**
	 * The website full logo path
	 * @field path
	 * @var mixed
	 */
	public $FullLogoPath = "asset/logo/full-logo.png";

	/**
	 * The main KeyWords of the website, these will effect on SEO and views
	 * @field array
	 * @var array
	 */
	public $KeyWords = [];

	/**
	 * The website Encoding
	 * @var string
	 * @category Language
	 */
	public $Encoding = "utf-8";
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
	 * Default response headers
	 * @internal
	 * @var array
	 */
	public $Headers = [];
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
	 * @template array [sm, n, lg, xl, xxl,...]
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

	/**
	 * A simple library to Session management
	 * @internal
	 */
	public \MiMFa\Library\Translate $Translate;
	/**
	 * Allow to translate all text by internal algorithms
	 * @var bool
	 * @category Language
	 */
	public $AllowTranslate = false;
	public $TranslateTableName = "Translate_Lexicon";
	public $TranslateTableNamePrefix = null;
	/**
	 * Allow to detect the client language automatically
	 * @var bool
	 * @category Language
	 */
	public $AutoDetectLanguage = false;
	/**
	 * Allow to update the language by translator automatically
	 * @var bool
	 * @category Language
	 */
	public $AutoUpdateLanguage = false;
	/**
	 * Allow to cache language for a fast rendering
	 * @var bool
	 * @category Language
	 */
	public $CacheLanguage = true;
	/**
	 * Default language to translate all text by internal algorithms
	 * @var string
	 * @category Language
	 */
	public $DefaultLanguage = null;
	/**
	 * The website default Direction
	 * @var string
	 * @category Language
	 */
	public $DefaultDirection = null;

	public function __construct()
	{
		Revise::Load($this);

		$this->Translate = new \MiMFa\Library\Translate(new \MiMFa\Library\DataTable(\_::$Back->DataBase, $this->TranslateTableName, $this->TranslateTableNamePrefix, \_::$Back->DataTableNameConvertors));
		if ($this->AllowTranslate || $this->AutoUpdateLanguage) {
			$this->Translate->AutoUpdate = $this->AutoUpdateLanguage;
			$this->Translate->AutoDetect = $this->AutoDetectLanguage;
			$this->Translate->Initialize(
				$this->DefaultLanguage,
				$this->DefaultDirection,
				$this->Encoding,
				$this->AllowTranslate && $this->CacheLanguage
			);
		}
		$this->DefaultMode = $this->CurrentMode = $this->GetMode($this->BackColor(0));
		$this->SwitchMode = received($this->SwitchRequest) ?? getMemo($this->SwitchRequest) ?? $this->SwitchMode;
		
		if ($this->SwitchMode) {
			$middle = $this->ForeColorPalette;
			$this->ForeColorPalette = $this->BackColorPalette;
			$this->BackColorPalette = $middle;
			$this->CurrentMode = $this->GetMode($this->BackColor(0));
		}

		$this->SenderEmail = $this->SenderEmail ?: createEmail("do-not-reply");
		$this->ReceiverEmail = $this->ReceiverEmail ?: createEmail("info");
	}

	public function __get($name)
	{
		return $this->Items[$this->PropertyName($name)] ?? null;
	}
	public function __set($name, $value)
	{
		$this->Items[$this->PropertyName($name)] = $value;
	}
	public function PropertyName($name)
	{
		return preg_replace("/\W+/", "", strToProper($name));
	}

	public function GetAccessCondition($tablePrefix = "")
	{
		if ($this->AllowTranslate)
			return $this->Translate->GetAccessCondition($tablePrefix);
		return null;
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
	 * @param int $index 0:sm, 1:n, 2:lg, 3:xl, 4:xxl,...
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
		return Local::GetUrl($this->LoopPalette($this->OverlayPalette, $index));
	}
	/**
	 * To get the Pattern image by index
	 * @param int $index 0:minimum, 1:normal, 2:medium, 3:maximum,...
	 */
	public function Pattern(int $index = 0)
	{
		return Local::GetUrl($this->LoopPalette($this->PatternPalette, $index));
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
	 * Interact with all specific script results of the client side one by one
	 * @param mixed $intents The front iterator JS codes like an array 
	 * @param mixed $callback The call back handler
	 * @example: iterateRequest("document.querySelectorAll('body input')", function(selectedItems)=>{ //do somework })
	 */
	function Bring($intents = null, $callback = null)
	{
		$callbackScript = "(data,err)=>{_(item).before(data,err); _(item).remove();}";
		$progressScript = "null";
		$timeout = 60000;
		$start = Internal::MakeStartScript(true);
		$end = Internal::MakeEndScript(true);
		$id = "S_" . getID(true);
		$intents = Convert::ToString($intents, ",", "{1}", "[{0}]", "[]");
		if (isStatic($callback))
			response(Struct::Script("$start for(item of $intents)(" . $callbackScript . ")(" .
				Script::Convert($callback) . ",item);_('#$id').remove();$end", null, ["id" => $id]));
		else
			response(Struct::Script(
				$callback ? "$start" .
				"for(item of $intents)sendInternal(null,{\"" . Internal::Set($callback) . '":item.outerHTML},' .
				"getQuery(item),$callbackScript,$callbackScript,null,$progressScript,$timeout);_('#$id').remove();$end"
				: $intents
				,
				null,
				["id" => $id]
			));
	}

	/**
	 * Get all specific parts of the client side
	 * @param mixed $selector The source selector
	 * @param mixed $callback The call back handler
	 * @example: get("body", function(selectedHtml)=>{ //do somework })
	 */
	public function Get($selector = null, $callback = null)
	{
		request("Array.from(document.querySelectorAll(" . Script::Convert($selector ?? $this->DefaultSourceSelector) . ").values().map(el=>el.outerHTML))", $callback);
	}
	/**
	 * Replace the output with a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function Set($selector = null, $handler = null, ...$args)
	{
		return beforeUsing(
			\_::$Address->Directory,
			"finalize",
			fn() => response(Struct::Script($this->MakeSetScript($selector, $handler, $args, false)))
		);
	}
	/**
	 * Make a script to
	 * Replace the output with a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function MakeSetScript($selector = null, $handler = null, $args = [], $direct = true)
	{
		return Internal::MakeScript(
			$handler,
			$args,
			"(data,err)=>_(" . Script::Convert($selector ?? $this->DefaultDestinationSelector) . ").replace(data??err)",
			direct: $direct,
			encrypt: false
		);
	}
	/**
	 * Delete a special part of client side
	 * @param mixed $selector The destination selector
	 */
	public function Delete($selector = "body")
	{
		return beforeUsing(
			\_::$Address->Directory,
			"finalize",
			fn() => $this->Append("body", Struct::Script($this->MakeDeleteScript($selector, false)))
		);
	}
	/**
	 * Make a script to Delete a special part of client side
	 * @param mixed $selector The destination selector
	 */
	public function MakeDeleteScript($selector = "body", $direct = true)
	{
		return Internal::MakeStartScript(direct: $direct) . "document.querySelectorAll(" . Script::Convert($selector ?? $this->DefaultSourceSelector) . ").forEach(el=>el.remove())" . Internal::MakeEndScript(direct: $direct);
	}
	/**
	 * Insert output before a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function Before($selector = "body", $handler = null, ...$args)
	{
		return beforeUsing(
			\_::$Address->Directory,
			"finalize",
			fn() => $this->Append("body", Struct::Script($this->MakeBeforeScript($selector, $handler, $args, false)))
		);
	}
	/**
	 * Make a script to
	 * Insert output before a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function MakeBeforeScript($selector = "body", $handler = null, $args = [], $direct = true)
	{
		return Internal::MakeScript(
			$handler,
			$args,
			"(data,err)=>_(" . Script::Convert($selector ?? $this->DefaultDestinationSelector) . ").before(data??err)"
			,
			direct: $direct,
			encrypt: false
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
		return beforeUsing(
			\_::$Address->Directory,
			"finalize",
			fn() => $this->Append("body", Struct::Script($this->MakeAfterScript($selector, $handler, $args, false)))
		);
	}
	/**
	 * Make a script to
	 * Insert output after a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function MakeAfterScript($selector = "body", $handler = null, $args = [], $direct = true)
	{
		return Internal::MakeScript(
			$handler,
			$args,
			"(data,err)=>_(" . Script::Convert($selector ?? $this->DefaultDestinationSelector) . ").after(data??err)",
			direct: $direct,
			encrypt: false
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
		return beforeUsing(
			\_::$Address->Directory,
			"finalize",
			fn() => $this->Append("body", Struct::Script($this->MakeFillScript($selector, $handler, $args, false)))
		);
	}
	/**
	 * Make a script to
	 * Print output inside a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function MakeFillScript($selector = "body", $handler = null, $args = [], $direct = true)
	{
		return Internal::MakeScript(
			$handler,
			$args,
			//"(data,err)=>document.querySelectorAll(" . Script::Convert($selector ?? $this->DefaultDestinationSelector) . ").forEach(l=>l.replaceChildren(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err)))"
			"(data,err)=>_(" . Script::Convert($selector ?? $this->DefaultDestinationSelector) . ").html(data??err)",
			direct: $direct,
			encrypt: false
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
		return beforeUsing(
			\_::$Address->Directory,
			"finalize",
			fn() => $this->Append("body", Struct::Script($this->MakePrependScript($selector, $handler, $args, false)))
		);
	}
	/**
	 * Make a script to
	 * Prepend output on a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function MakePrependScript($selector = "body", $handler = null, $args = [], $direct = true)
	{
		return Internal::MakeScript(
			$handler,
			$args,
			"(data,err)=>_(" . Script::Convert($selector ?? $this->DefaultDestinationSelector) . ").prepend(data??err)",
			direct: $direct,
			encrypt: false
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
		return beforeUsing(
			\_::$Address->Directory,
			"finalize",
			fn() => $this->Append("body", Struct::Script($this->MakeAppendScript($selector, $handler, $args, false)))
		);
	}
	/**
	 * Make a script to
	 * Append output on a special part of client side
	 * @param mixed $selector The destination selector
	 * @param mixed $handler The data that is ready to print
	 * @param mixed $args Handler input arguments
	 */
	public function MakeAppendScript($selector = "body", $handler = null, $args = [], $direct = true)
	{
		return Internal::MakeScript(
			$handler,
			$args,
			"(data,err)=>_(" . Script::Convert($selector ?? $this->DefaultDestinationSelector) . ").append(data??err)",
			direct: $direct,
			encrypt: false
		);
	}
}