<?php
namespace MiMFa\Module;
library("Style");
library("Convert");
use MiMFa\Library\Html;
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
      * The custom classes for the module
      * @var enum-string
      * @small
      */
     public $Name = null;
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
     public $Tag = "div";
     /**
      * The specific tag name to add Title
      * @var enum-string
      * @small
      */
     public $TitleTag = "h3";
     /**
      * The specific tag name to add Description
      * @var enum-string
      * @small
      */
     public $DescriptionTag = "div";
     /**
      * The specific tag name to add Content
      * @var enum-string
      * @small
      */
     public $ContentTag = null;
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
      * The Module Style
      * @var Style
      */
     public null|string|Style $Style = null;
     /**
      * To custom Styles
      * @var string
      * @code CSS
      */
     public $Styles = null;
     /**
      * To custom Scripts
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

     public function __construct()
     {
          parent::__construct();
          $this->Router->Get()->Unset()->Set(fn() => Convert::ToString(function () {
               if ($this->Styles === null)
                    yield $this->GetStyle();
               elseif (!isEmpty($this->Styles))
                    yield Html::Style($this->Styles);
               yield $this->GetOpenTag() . $this->Get() . $this->GetCloseTag();
               if ($this->Scripts === null)
                    yield $this->GetScript();
               elseif (!isEmpty($this->Scripts))
                    yield Html::Script($this->Scripts);
          }));
     }

     /**
      * Get the Open tag of the element
      * @param string|null The specific TagName, set null for default
      */
     public function GetOpenTag($tag = null)
     {
          $st = null;
          if (isValid($this->Style))
               $st = is_string($this->Style) ? $this->Style : $this->Style->Get();
          if (isValid($tag ?? $this->Tag))
               return join("", ["<", ($tag ?? $this->Tag ?? "div"), " ", Html::Attributes($this->GetDefaultAttributes(), $this->Attachments), isValid($st) ? " style=\"{$st}\"" : "", ">"]);
          elseif (isValid($st))
               return "<style>.{$this->Name}{ $st }</style>";
          return null;
     }
     /**
      * Get the Close tag of the element
      * @param string|null The specific TagName, set null for default
      */
     public function GetCloseTag($tag = null)
     {
          if (isValid($tag ?? $this->Tag))
               return "</" . ($tag ?? $this->Tag ?? "div") . ">" . Convert::ToString($this->Attachments);
          return null;
     }

     /**
      * Get the default module Attributes
      * @return array
      */
     public function GetDefaultAttributes()
     {
          return [
               [
                    "id" => $this->Id,
                    "class" => $this->Name . ' ' . $this->Class . $this->GetScreenClass()
               ],
               (isEmpty($this->Attributes) ? [] : (is_array($this->Attributes) ? $this->Attributes : [Convert::ToString($this->Attributes, " ", "{0}={1} ")])),
               (count($this) < 1 ? [] : $this->__toArray())
          ];
     }
     /**
      * Get the default module Screen Attributes
      * @return string
      */
     public function GetScreenClass()
     {
          return (isValid($this->VisibleFromScreenSize) ? " " . $this->VisibleFromScreenSize . "-visible" : "") .
               (isValid($this->InvisibleFromScreenSize) ? " " . $this->InvisibleFromScreenSize . "-invisible" : "") .
               (isValid($this->ShowFromScreenSize) ? " " . $this->ShowFromScreenSize . "-show" : "") .
               (isValid($this->HideFromScreenSize) ? " " . $this->HideFromScreenSize . "-hide" : "");
     }

     /**
      * Get the default module Styles
      */
     public function GetStyle()
     {
          return null;
     }
     /**
      * Get the default module Scripts
      */
     public function GetScript()
     {
          return null;
     }

     public function GetTitle($attrs = null)
     {
          return Convert::ToString(function () use ($attrs) {
               $attrs = Html::Attributes($attrs, $atcm);
               if (isValid($this->Title)) {
                    yield (isValid($this->TitleTag) ? "<" . $this->TitleTag . " $attrs>" : "");
                    if (is_string($this->Title))
                         yield __($this->Title, styling: false);
                    elseif (is_callable($this->Title))
                         ($this->Title)($attrs);
                    else
                         yield $this->Title;
                    yield (isValid($this->TitleTag) ? "</" . $this->TitleTag . ">" : "");
               }
          });
     }
     public function GetDescription($attrs = null)
     {
          return Convert::ToString(function () use ($attrs) {
               $attrs = Html::Attributes($attrs, $atcm);
               if (isValid($this->Description)) {
                    yield (isValid($this->DescriptionTag) ? "<" . $this->DescriptionTag . " $attrs>" : "");
                    if (is_string($this->Description))
                         yield __($this->Description);
                    elseif (is_callable($this->Description))
                         ($this->Description)($attrs);
                    else
                         yield $this->Description;
                    yield (isValid($this->DescriptionTag) ? "</" . $this->DescriptionTag . ">" : "");
               }
          });
     }
     public function GetContent($attrs = null)
     {
          return Convert::ToString(function () use ($attrs) {
               $attrs = Html::Attributes($attrs, $atcm);
               if (isValid($this->Content)) {
                    yield (isValid($this->ContentTag) ? "<" . $this->ContentTag . " $attrs>" : "");
                    if (is_string($this->Content))
                         yield __($this->Content);
                    elseif (is_callable($this->Content))
                         ($this->Content)($attrs);
                    else
                         yield $this->Content;
                    yield (isValid($this->ContentTag) ? "</" . $this->ContentTag . ">" : "");
               }
               yield Convert::ToString($this->Children);
          });
     }

     /**
      * Get all the HTML document and elements of the Module
      * @return string
      */
     public function Get()
     {
          return join("", [$this->GetTitle(), $this->GetDescription(), $this->GetContent()]);
     }
     public function Handler($received = null)
     {
          return null;
     }

     public function Handle()
     {
          $translate = \_::$Config->AllowTranslate;
          $analyze = \_::$Config->AllowTextAnalyzing;
          \_::$Config->AllowTranslate = $translate && $this->AllowTranslate;
          \_::$Config->AllowTextAnalyzing = $analyze && $this->AllowTextAnalyzing;
          $output = $this->BeforeHandle() . parent::Handle() . $this->AfterHandle();
          \_::$Config->AllowTranslate = $translate;
          \_::$Config->AllowTextAnalyzing = $analyze;
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
      * Handle Or ReHandle if is Handled.
      */
     public function DoHandle()
     {
          if ($this->Handled)
               return $this->ReHandle();
          else
               return $this->Handle();
     }

     /**
      * Echo whole the Document contains Elements, Styles, Scripts, etc. completely.
      */
     public function Render()
     {
          if ($this->Visual) {
               \Res::Render($this->Handle());
               $this->Rendered++;
               return null;
          }
          return $this->Handle();
     }
     /**
      * Echo whole the Document contains Elements except Styles and Scripts.
      */
     public function ReRender()
     {
          if ($this->Visual) {
               \Res::Render($this->ReHandle());
               $this->Rendered++;
               return null;
          }
          return $this->ReHandle();
     }
     /**
      * Draw Or ReDraw if is Rendered.
      */
     public function DoRender()
     {
          if ($this->Rendered)
               return $this->ReRender();
          else
               return $this->Render();
     }


     public function ToString()
     {
          ob_start();
          if ($this->Rendered || $this->Handled)
               $output = $this->ReRender();
          else
               $output = $this->Render();
          return ob_get_clean() ?? $output;
     }
} ?>