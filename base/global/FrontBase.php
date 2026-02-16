<?php

use MiMFa\Library\Convert;
use MiMFa\Library\Struct;
use MiMFa\Library\Internal;
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
	/**
	 * Additional items to use in back-end
	 * @var array
	 */
	public $Items = [];

	/**
	 * The website owner name
	 * @var mixed
	 * @category Information
	 */
	public $Owner = null;
	/**
	 * The website owner full name
	 * @var mixed
	 * @category Information
	 */
	public $FullOwner = null;
	/**
	 * Descriptions about the website owner
	 * @field texts
	 * @category Information
	 * @var mixed
	 */
	public $OwnerDescription = null;
	/**
	 * A full version of descriptions about the website owner
	 * @field content
	 * @category Information
	 * @var mixed
	 */
	public $FullOwnerDescription = null;
	/**
	 * The website owner and name
	 * @category Information
	 * @var mixed
	 */
	public $Name = null;
	/**
	 * The website full owner and full name
	 * @category Information
	 * @var mixed
	 */
	public $FullName = null;
	/**
	 * The short slogan of the website
	 * @field string
	 * @category Information
	 * @var mixed
	 */
	public $Slogan = null;
	/**
	 * The more detailed slogan of the website
	 * @field texts
	 * @category Information
	 * @var mixed
	 */
	public $FullSlogan = null;
	/**
	 * The short description of the website
	 * @field texts
	 * @category Information
	 * @var mixed
	 */
	public $Description = null;
	/**
	 * The more detailed description of the website
	 * @field content
	 * @category Information
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
     * The status of all server response: 400, 404, 500, etc.
     * @default null
     * @var mixed
     * @category Security
     */
    public $StatusMode = null;

    /**
     * The default view name to show when restriction
     * @var string
     * @category Security
     */
    public $RestrictionRouteName = "403";
    /**
     * Default message to show when restriction
     * @var string
     * @category Security
     */
    public $RestrictionContent = "Unfortunately, you have no access to the site now!<br>Please try a few minutes later...";

    /**
     * The view name to show pages
     * @var string
     * @default "main"
     * @category Template
     */
    public string $DefaultRouteName = "main";
	/**
	 * The website default template class
	 * @default "Template"
	 * @field value
	 * @category Template
	 * @var string
	 */
	public string $DefaultTemplateName = "Template";
	/**
	 * The website view name
	 * @default "main"
	 * @field value
	 * @category Template
	 * @var string
	 */
	public string $DefaultViewName = "main";
	/**
	 * @field value
	 * @category Template
	 * @var string
	 */
	public $DefaultSourceSelector = "body";
	/**
	 * @field value
	 * @category Template
	 * @var string
	 */
	public $DefaultDestinationSelector = "body";

	/**
	 * The main path
	 * @field path
	 * @category Information
	 * @var mixed
	 */
	public $Path = null;
	/**
	 * The website main logo path
	 * @field image
	 * @category Template
	 * @var mixed
	 */
	public $LogoPath = "asset/logo/logo.png";
	/**
	 * The website brand logo path
	 * @field image
	 * @category Template
	 * @var mixed
	 */
	public $BrandLogoPath = "asset/logo/brand-logo.png";
	/**
	 * The website full logo path
	 * @field image
	 * @category Template
	 * @var mixed
	 */
	public $FullLogoPath = "asset/logo/full-logo.png";

	/**
	 * The main KeyWords of the website, these will effect on SEO and views
	 * @field array
	 * @category Information
	 * @var array
	 */
	public $KeyWords = [];

	/**
	 * The Date Time Zone
	 * @var string
	 * @field value
	 * @category Time
	 */
	public $DateTimeZone = "UTC";
	/**
	 * The Date Time Locale
	 * @var string
	 * @field value
	 * @category Time
	 */
	public $DateTimeLocale = "en-US";
	/**
	 * The Date Time Format
	 * @var string
	 * @example: "Y-m-d H:i:s" To show like 2018-08-10 14:46:45
	 * @field value
	 * @category Time
	 */
	public $DateTimeFormat = "Y-m-d H:i:s";
	/**
	 * Current Date Time
	 * @var string
	 * @field value
	 * @category Time
	 */
	public $CurrentDateTime = "now";
	/**
	 * Date Time Stamp Seconds Offset (TSO)
	 * @var int
	 * @category Time
	 */
	public $TimeStampOffset = 0;

	/**
	 * Default response headers
	 * @internal
	 * @category Render
	 * @var array
	 */
	public $Headers = [];
	/**
	 * Default page head Packages
	 * @internal
	 * @field html
	 * @category Render
	 * @var array
	 */
	public $Libraries = [];
	/**
	 * The custom head packages
	 * @field html
	 * @category Render
	 * @var array
	 */
	public $Initials = [];
	/**
	 * The custom top of body's tags
	 * @field html
	 * @category Render
	 * @var array
	 */
	public $Mains = [];
	/**
	 * The custom top of body's tags
	 * @field html
	 * @category Render
	 * @var array
	 */
	public $Finals = [];

	/**
	 * Full Colors Palette
	 * @field array<color>
	 * @template array [Black, Red, Green, Blue, Yellow, Cyan, Violet, White]
	 * @category Template
	 * @var mixed
	 */
	public $ColorPalette = array("#212529", "#dc3545", "#198754", "#0d6efd", "#ffc107", "#0dcaf0", "#6f42c1", "#f8f9fa");
	/**
	 * Fore Colors Palette
	 * @field array<color>
	 * @template array [normal, inside, outside, special, special-input, special-output]
	 * @category Template
	 * @var mixed
	 */
	public $ForeColorPalette = array("#151515", "#202020", "#101010", "#040506", "#3aa3e9", "#fdfeff");
	/**
	 * Back Colors Palette
	 * @field array<color>
	 * @template array [normal, inside, outside, special, special-input, special-output]
	 * @category Template
	 * @var mixed
	 */
	public $BackColorPalette = array("#fdfeff", "#fafbfc", "#fdfeff", "#fafcfd", "#fdfeff", "#3aa3e9");
	/**
	 * Sizes Palette
	 * @field array<size>
	 * @template array [sm, n, lg, xl, xxl,...]
	 * @category Template
	 * @var mixed
	 */
	public $SizePalette = array("2.3vh", "2.4vh", "2.6vh", "3vh", "3.6vh", "4.4vh", "5.4vh");


	/**
	 * Allow to reduce size of documents for increasing site speed
	 * @var bool
	 * @category Optimization
	 */
	public $AllowReduceSize = true;
	/**
	 * Allow to analyze all text and signing them, to improve the website's SEO
	 * @var bool
	 * @category Optimization
	 */
	public $AllowTextAnalyzing = false;
	/**
	 * Allow to analyze all text and linking contents to their called names or titles, to improve the website's SEO
	 * @var bool
	 * @category Optimization
	 */
	public $AllowContentReferring = false;
	/**
	 * Allow to analyze all text and linking categories to their called names or titles, to improve the website's SEO
	 * @var bool
	 * @category Optimization
	 */
	public $AllowCategoryReferring = false;
	/**
	 * Allow to analyze all text and linking tags to their called names or titles, to improve the website's SEO
	 * @var bool
	 * @category Optimization
	 */
	public $AllowTagReferring = false;
	/**
	 * Allow to analyze all text and linking users to their called names, to improve the website's SEO
	 * @var bool
	 * @category Optimization
	 */
	public $AllowUserReferring = false;
	
	
	/**
	 * Default language to translate all text by internal algorithms
	 * @var string
	 * @category Language
	 */
	public $Language = null;
	/**
	 * The website default Direction
	 * @var string
	 * @category Language
	 */
	public $Direction = null;
	/**
	 * The website Encoding
	 * @var string
	 * @category Language
	 */
	public $Encoding = "utf-8";

	/**
	 * A simple library to Session management
	 * @internal
	 * @category Language
	 */
	public \MiMFa\Library\Translate $Translate;
	/**
	 * Allow to translate all text by internal algorithms
	 * @var bool
	 * @category Language
	 */
	public $AllowTranslate = false;
	/**
	 * @category Language
	 * @field value
	 * @var string
	 */
	public $TranslateTableName = "Translate_Lexicon";
	/**
	 * @category Language
	 * @field value
	 */
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
	

	public function __construct()
	{
		Revise::Load($this);

		$this->Translate = new \MiMFa\Library\Translate(new \MiMFa\Library\DataTable(\_::$Back->DataBase, $this->TranslateTableName, $this->TranslateTableNamePrefix, \_::$Back->DataTableNameConvertors));
		if ($this->AllowTranslate || $this->AutoUpdateLanguage) {
			$this->Translate->AutoUpdate = $this->AutoUpdateLanguage;
			$this->Translate->AutoDetect = $this->AutoDetectLanguage;
			$this->Translate->Initialize(
				$this->Language,
				$this->Direction,
				$this->Encoding,
				$this->AllowTranslate && $this->CacheLanguage
			);
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
		return new (template($name, $data, alternative: $this->DefaultTemplateName))();
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
	 * To get the Size by index
	 * @param int $index 0:sm, 1:n, 2:lg, 3:xl, 4:xxl,...
	 */
	public function Size(int $index = 0)
	{
		return $this->LimitPalette($this->SizePalette, $index);
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
	public function Bring($intents = null, $callback = null)
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
	 * Replace the output with all the document in the client side
	 * @param mixed $output The data that is ready to print
	 * @param string $address The url to show without updating the page
	 */
	public function Cover($output = null, $address = null)
	{
		return response(Struct::Script(
			Internal::MakeScript(
				$output,
				null,
				"(data,err)=>{"
				($output ? "document.open();document.write(data??err);document.close();" : "") .
				($address ? "window.history.pushState(null, null, `" . getFullUrl($address) . "`);" : "") .
				"}"
			)
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
			\_::$Address->GlobalDirectory,
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
			\_::$Address->GlobalDirectory,
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
			\_::$Address->GlobalDirectory,
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
			\_::$Address->GlobalDirectory,
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
			\_::$Address->GlobalDirectory,
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
			\_::$Address->GlobalDirectory,
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
			\_::$Address->GlobalDirectory,
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