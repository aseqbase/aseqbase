<?php
namespace MiMFa\Module;
library("Style");
library("Convert");

use Generator;
use MiMFa\Library\Struct;
use MiMFa\Library\Style;
use MiMFa\Library\Convert;
/**
 * One or some related classes, that contain one or more routines. aseqbase contains several different modules, and each module serves unique and separate important operations.
 * Module tends to refer to larger bundles. There's often a set of interfaces and the module tends to be able to stand on its own.
 *
 * Guide for Documentations
 *
 *○ Use @var {bool, int, float, string, array<datatype>, etc.}: to indicate the variable or constant type. other useful type can be:
 *    enum-string: to indicate the legal string name for a variable
 *    class-string: to indicate the exist class name
 *    interface-string: to indicate the exist interface name
 *    lowercase-string, non-empty-string, non-empty-lowercase-string: to indicate a non empty string, lowercased or both at once
 *○ Use @param datatype $paramname [description]: to indicate comments of a function parameter
 *○ Use @small, @medium, @large: to indicate the size of input box
 *○ Use @category categoryname: to specify a category to organize the documented element's package into
 *○ Use @internal: to indicate the property should not be visible in the front-end
 *○ Use @access {public, private, protected}: to indicate access control documentation for an element, for example @access private prevents documentation of the following element (if enabled)
 *○ Use @version versionstring [unspecified format]: to indicate the version of any element, including a page-level block
 *○ Use @example /path/to/example.php [description]: to include an external example file with syntax highlighting
 *○ Use @link URL [linktext]: to display a hyperlink to a URL in the documentation
 *○ Use @see {file.ext, elementname, class::methodname(), class::$variablename, functionname(), function functionname}: to display a link to the documentation for an element, there can be unlimited number of values separated by commas
 *○ Use @author authorname: to indicate the author name of everythings. By default the authorname of everything are  Mohammad Fathi
 *○ Use @copyright copyright [information]: to document the copyright information of any element that can be documented. The default copyrights of everything are  for MiMFa Development Group
 *○ Use @license URL [licensename]: to display a hyperlink to a URL for a license
 *
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 *@example Image.php
 */
class Module extends \Base
{
     /**
      * The custom classes for the module
      * @var enum-string
      * @small
      * @category
      */
     public $Id = null;
     /**
      * The main class name for the module
      * @var enum-string
      * @small
      */
     public $MainClass = null;
     /**
      * The custom classes for the module
      * @var string
      * @medium
      */
     public $Class = null;
     /**
      * The special string/html for the Title of the module
      * @var string
      * @medium
      * @
      */
     public $Title = null;
     /**
      * The special string/html for the Description of the module
      * @var string
      * @large
      */
     public $Description = null;
     /**
      * The special string/html for the Content of the module
      * @var string
      * @large
      */
     public $Content = null;
     /**
      * The specific tag name to add Module
      * @var enum-string
      * @small
      */
     public string|null $TagName = "div";
     /**
      * The specific tag name to add Title
      * @var enum-string
      * @small
      */
     public string|null $TitleTagName = "h3";
     public $TitleClass = "title";
     /**
      * The specific tag name to add Description
      * @var enum-string
      * @small
      */
     public string|null $DescriptionTagName = "div";
     public $DescriptionClass = "description";
     /**
      * The specific tag name to add Content
      * @var enum-string
      * @small
      */
     public string|null $ContentTagName = null;
     public $ContentClass = "content";
     /**
      * Attached Attributes of the main tag of this module
      * @var array<string>|string
      * @medium
      */
     public $Attributes = null;
     /**
      * Attachments of the main tag of this module
      * @var mixed
      * @medium
      */
     public $Attachments = null;
	/**
	 * Additional Children of the object
	 * @internal
	 * @field collection
	 * @var mixed
	 */
	public $Items = null;
     /**
      * The Module Style
      * @var Style
      */
     public null|string|Style $Style = null;
     /**
      * To replace your custom Styles instead of defaults
      * @var string
      * @code CSS
      */
     public $Styles = null;
     /**
      * To replace your custom Structs instead of defaults
      * @var string
      * @code CSS
      */
     public $Structs = null;
     /**
      * To replace your custom Scripts instead of defaults
      * @var string
      * @code JS
      */
     public $Scripts = null;
     /**
      * Show this module when the screen size is one of the options \ScreenSize
      * @var \ScreenSize|string
      */
     public $ShowFromScreenSize = null;
     /**
      * Hide this module when the screen size is one of the options \ScreenSize
      * @var \ScreenSize|string
      */
     public $HideFromScreenSize = null;
     /**
      * Visible this module when the screen size is one of the options \ScreenSize
      * @var \ScreenSize|string
      */
     public $VisibleFromScreenSize = null;
     /**
      * Invisible this module when the screen size is one of the options \ScreenSize
      * @var \ScreenSize|string
      */
     public $InvisibleFromScreenSize = null;

     /**
      * Allow to analyze all text and signing them, to improve the website SEO
      * @var bool
      * @category Optimization
      */
     public $AllowTextAnalyzing = true;
     /**
      * Allow to translate all text by internal algorithms
      * @var bool
      * @category Optimization
      */
     public $AllowTranslate = true;

     public $Visual = true;
     public $Printable = true;


     public function __construct()
     {
          parent::__construct();
		$this->MainClass = \_::$Back->EncryptNames ? (substr($this->Get_Namespace(), 0, 1) . RandomString(10)) : ($this->MainClass ?? $this->Name) . "_" . $this->Get_Namespace();
     }


     public function Get()
     {
          if ($this->Styles === null)
               yield $this->GetStyle();
          elseif ($this->Styles)
               yield Struct::Style($this->Styles);
          if ($this->Structs === null)
               yield $this->GetStruct();
          elseif ($this->Structs)
               yield Struct::Convert($this->Structs);
          if ($this->Scripts === null)
               yield $this->GetScript();
          elseif ($this->Scripts)
               yield Struct::Script($this->Scripts);
     }

     /**
      * Get the default module Styles
      * @return Generator|string|null
      */
     public function GetStyle()
     {
          return null;
     }
     /**
      * Get the default module Struct
      * @return Generator|string|null
      */
     public function GetStruct()
     {
          yield $this->GetBefore();
          yield $this->GetInner();
          yield $this->GetAfter();
     }
     /**
      * Get the default module Scripts
      * @return Generator|string|null
      */
     public function GetScript()
     {
          return null;
     }

     /**
      * Get the Open tag of the element
      * @return string|null
      */
     public function GetBefore()
     {
          $st = null;
          if ($this->Style)
               $st = is_string($this->Style) ? $this->Style : $this->Style->Get();
          if ($this->TagName) {
               $attr = Struct::Attributes($this->GetDefaultAttributes(), $this->Attachments, $inners, $outers);
               return join("", [$outers, "<{$this->TagName} ", $attr, $st ? " style=\"{$st}\">" : ">", $inners]);
          } elseif ($st)
               return "<style>.{$this->MainClass}{ $st }</style>";
          return null;
     }
     /**
      * Get all the HTML document and elements of the Module
      * @return Generator|string|null
      */
     public function GetInner()
     {
          yield $this->GetTitle();
          yield $this->GetDescription();
          yield $this->GetContent();
     }
     /**
      * Get the Close tag of the element
      * @return string|null
      */
     public function GetAfter()
     {
          if ($this->TagName)
               return "</{$this->TagName}>" . Convert::ToString($this->Attachments);
          return null;
     }

     /**
      * @return string|null
      */
     public function GetTitle($args = [])
     {
          return Convert::ToString(function () use ($args) {
               $args = Struct::Attributes([["class" => $this->TitleClass], $args], $atcm, $inners, $outers);
               if (isValid($this->Title)) {
                    yield (isValid($this->TitleTagName) ? join("", [$outers, "<", $this->TitleTagName, " $args>", $inners]) : ($inners . $outers));
                    if (is_string($this->Title))
                         yield __($this->Title);
                    elseif (is_callable($this->Title))
                         yield ($this->Title)($args);
                    else
                         yield $this->Title;
                    yield (isValid($this->TitleTagName) ? join("", ["</", $this->TitleTagName . ">"]) : "");
               }
          });
     }
     /**
      * @return string|null
      */
     public function GetDescription($args = [])
     {
          return Convert::ToString(function () use ($args) {
               $args = Struct::Attributes([["class" => $this->DescriptionClass], $args], $atcm, $inners, $outers);
               if (isValid($this->Description)) {
                    yield (isValid($this->DescriptionTagName) ? join("", [$outers, "<", $this->DescriptionTagName, " $args>", $inners]) : ($inners . $outers));
                    if (is_string($this->Description))
                         yield __($this->Description);
                    elseif (is_callable($this->Description))
                         yield ($this->Description)($args);
                    else
                         yield $this->Description;
                    yield (isValid($this->DescriptionTagName) ? join("", ["</" . $this->DescriptionTagName . ">"]) : "");
               }
          });
     }
     /**
      * @return string|null
      */
     public function GetContent($args = [])
     {
          return Convert::ToString(function () use ($args) {
               $args = Struct::Attributes([["class" => $this->ContentClass], $args], $atcm, $inners, $outers);
               if (isValid($this->Content)) {
                    yield (isValid($this->ContentTagName) ? join("", [$outers, "<", $this->ContentTagName, " $args>", $inners]) : ($inners . $outers));
                    if (is_string($this->Content))
                         yield __(Struct::Convert($this->Content));
                    elseif (is_callable($this->Content))
                         yield ($this->Content)($args);
                    else
                         yield $this->Content;
                    yield (isValid($this->ContentTagName) ? join("", ["</", $this->ContentTagName, ">"]) : "");
               }
          });
     }

     /**
      * Get the default module Attributes
      * @return array
      */
     public function GetDefaultAttributes()
     {
          return [
               ($this->Id ? ["id" => $this->Id] : []),
               ["class" => $this->MainClass . ' ' . $this->Class . $this->GetScreenClass() . ($this->Printable ? '' : ' view unprintable')],
               (isEmpty($this->Attributes) ? [] : (is_array($this->Attributes) ? $this->Attributes : [Convert::ToString($this->Attributes, " ", "{0}={1} ")])),
               ($this->__toArray())
          ];
     }
     /**
      * Get the default module Screen Attributes
      * @return string
      */
     public function GetScreenClass()
     {
          return (isValid($this->VisibleFromScreenSize) ? " view " . $this->VisibleFromScreenSize . "-visible" : "") .
               (isValid($this->InvisibleFromScreenSize) ? " view " . $this->InvisibleFromScreenSize . "-invisible" : "") .
               (isValid($this->ShowFromScreenSize) ? " view " . $this->ShowFromScreenSize . "-show" : "") .
               (isValid($this->HideFromScreenSize) ? " view " . $this->HideFromScreenSize . "-hide" : "");
     }


     public function Handler($received = null)
     {
          return null;
     }

     public function Handle()
     {
          $translate = \_::$Front->AllowTranslate;
          $analyze = \_::$Front->AllowTextAnalyzing;
          \_::$Front->AllowTranslate = $translate && $this->AllowTranslate;
          \_::$Front->AllowTextAnalyzing = $analyze && $this->AllowTextAnalyzing;
          $output = Convert::ToString([$this->BeforeHandle(), parent::Handle(), $this->AfterHandle()]);
          \_::$Front->AllowTranslate = $translate;
          \_::$Front->AllowTextAnalyzing = $analyze;
          return $output;
     }
     /**
      * Get in the handle function to call before everything.
      */
     public function BeforeHandle()
     {
          return null;
     }
     /**
      * Get in the handle function to call after everything.
      */
     public function AfterHandle()
     {
          return null;
     }
     /**
      * Get whole the Document contains Elements except Styles and Scripts.
      */
     public function ReHandle()
     {
          $style = $this->Styles;
          $script = $this->Scripts;
          $this->Styles = "";
          $this->Scripts = "";
          $output = $this->Handle();
          $this->Styles = $style;
          $this->Scripts = $script;
          return $output;
     }

     /**
      * Echo whole the Document contains Elements, Styles, Scripts, etc. completely.
      */
     public function Render()
     {
          if ($this->Visual) {
               response($this->Handle());
               $this->Rendered++;
               return null;
          }
          return $this->Handle();
     }

     public function ToString()
     {
          ob_start();
          $output = null;
          if ($this->Rendered || $this->Handled) {
               if ($this->Visual) {
                    response($this->ReHandle());
                    $this->Rendered++;
               } else
                    $output = $this->ReHandle();
          } else
               $output = $this->Render();
          return ob_get_clean() ?? $output;
     }


	public function AddItem($child)
	{
		if (is_null($this->Items))
			$this->Items = array();
		//if(!is_null($child)) $child = is_subclass_of($child,"Base")? function()use($child){ $child->ToString(); }:$child;
		if (is_string($this->Items))
			$this->Items .= Convert::ToString($child);
		else
			array_push($this->Items, $child);
		return true;
	}
	public function RemoveItem($child)
	{
		if (is_null($this->Items))
			$this->Items = array();
		//if(!is_null($child)) $child = is_subclass_of($child,"Base")? function() use($child){ $child->ToString(); }:$child;
		if (is_string($this->Items)) {
			$this->Items = str_replace(Convert::ToString($child), "", $this->Items);
			return true;
		} else {
			$key = array_search($child, $this->Items);
			if ($key) {
				unset($this->Items[$key]);
				return true;
			}
		}
		return false;
	}

}