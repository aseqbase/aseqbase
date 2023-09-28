<?php
namespace MiMFa\Module;
LIBRARY("Style");
LIBRARY("Convert");
use MiMFa\Library\Style;
use MiMFa\Library\Convert;
/**
 * One or some related classes, that contain one or more routines. aseqbase contains several different modules, and each module serves unique and separate important operations.
 * Module tends to refer to larger bundles. There's often a set of interfaces and the module tends to be able to stand on its own.
 *
 * Guide for Documentations
 *
 *○ Use @var {bool, int, float, string, array<datatype>, etc.}: to indicate the variable or constant type. other useful type can be:
	enum-string: to indicate the legal string name for a variable
	class-string: to indicate the exist class name
	interface-string: to indicate the exist interface name
	lowercase-string, non-empty-string, non-empty-lowercase-string: to indicate a non empty string, lowercased or both at once
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
class Module extends \Base{
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
     * To replace the custom Styles with the defaults if true, otherwise append them to the defaults
     * @var bool
     */
	public $AllowDefaultStyles = true;
	/**
     * The Module Style
     * @var Style
     */
	public null|Style $Style = null;
	/**
     * To custom Styles
     * @var string
     * @code CSS
     */
	public $Styles = null;
	/**
     * To replace the custom Scripts with the defaults if true, otherwise append them to the defaults
     * @var bool
	 */
	public $AllowDefaultScripts = true;
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

	public $OneTimeStyle = false;
	public $OneTimeScript = false;

	public function __construct(){
        parent::__construct();
	}

	/**
     * Echo the Open tag of the element
     * @param string|null The specific TagName, set null for default
     */
	public function EchoOpenTag($tag=null){
        echo $this->GetOpenTag($tag);
    }
	/**
     * Get the Open tag of the element
     * @param string|null The specific TagName, set null for default
     */
	public function GetOpenTag($tag=null){
		$st = null;
		if(isValid($this->Style)) $st = $this->Style->Get();
		if(isValid($tag??$this->Tag)) return join("",["<",($tag??$this->Tag??"div")," ",$this->GetDefaultAttributes(), isValid($st)?" style=\"{$st}\"":"",">"]);
		elseif(isValid($st)) return "<style>.{$this->Name}{ $st }</style>";
        return null;
    }
	/**
     * Echo the Close tag of the element
     * @param string|null The specific TagName, set null for default
     */
	public function EchoCloseTag($tag=null){
		echo $this->GetCloseTag($tag);
	}
	/**
     * Get the Close tag of the element
     * @param string|null The specific TagName, set null for default
     */
	public function GetCloseTag($tag=null){
		if(isValid($tag??$this->Tag)) return "</".($tag??$this->Tag??"div").">";
        return null;
	}

	/**
     * Get the default module Attributes
     * @return string
     */
	public function GetDefaultAttributes(){
		return
			$this->GetAttribute("id",$this->Id).
			$this->GetAttribute(" class",$this->Name.' '.$this->Class.
				(isValid($this->VisibleFromScreenSize)?" ".$this->VisibleFromScreenSize."-visible":"").
				(isValid($this->InvisibleFromScreenSize)?" ".$this->InvisibleFromScreenSize."-invisible":"").
				(isValid($this->ShowFromScreenSize)?" ".$this->ShowFromScreenSize."-show":"").
				(isValid($this->HideFromScreenSize)?" ".$this->HideFromScreenSize."-hide":"")
			).
			(isValid($this->Attributes)?" ".Convert::ToString($this->Attributes," ","=","{0}={1} "):"");
	}
	/**
     * Create a standard Attribute and its value for a tag
     * @param string $name
     * @param string|null $value
	 * @return string
	 */
	public function GetAttribute($name,$value){
		return isValid($value)?("$name=\"$value\""):"";
	}

	/**
     * Echo the default module Styles
     */
	public function EchoStyle(){
        echo $this->GetStyle();
    }
	/**
     * Get the default module Styles
     */
	public function GetStyle(){
        return null;
    }

	/**
     * Echo the default module Scripts
     */
	public function EchoScript(){
        echo $this->GetScript();
    }
	/**
     * Get the default module Scripts
     */
	public function GetScript(){
        return null;
    }

	public function EchoTitle($attrs = null){
		echo $b = $this->GetTitle($attrs);
        return $b != "";
    }
	public function GetTitle($attrs = null){
		return Convert::ToString(function()use($attrs){
			if(isValid($this->Title)){
				yield (isValid($this->TitleTag)?"<".$this->TitleTag." $attrs>":"");
				if(is_string($this->Title))
					yield __($this->Title);
				elseif(is_callable($this->Title))
					($this->Title)($attrs);
				else yield $this->Title;
				yield (isValid($this->TitleTag)?"</".$this->TitleTag.">":"");
			}
        });
    }
	public function EchoDescription($attrs = null){
		echo $b = $this->GetDescription($attrs);
        return $b != "";
    }
	public function GetDescription($attrs = null){
		return Convert::ToString(function()use($attrs){
			if(isValid($this->Description)){
				yield (isValid($this->DescriptionTag)?"<".$this->DescriptionTag." $attrs>":"");
				if(is_string($this->Description))
					yield __($this->Description);
				elseif(is_callable($this->Description))
					($this->Description)($attrs);
				else yield $this->Description;
				yield (isValid($this->DescriptionTag)?"</".$this->DescriptionTag.">":"");
			}
        });
    }
	public function EchoContent($attrs = null){
		echo $b = $this->GetContent($attrs);
        return $b != "";
    }
    public function GetContent($attrs = null){
		return Convert::ToString(function()use($attrs){
            if(isValid($this->Content)){
                yield (isValid($this->ContentTag)?"<".$this->ContentTag." $attrs>":"");
                if(is_string($this->Content))
                    yield __($this->Content);
                elseif(is_callable($this->Content))
                    ($this->Content)($attrs);
                else yield $this->Content;
                yield (isValid($this->ContentTag)?"</".$this->ContentTag.">":"");
            }
            yield Convert::ToString($this->Children);
        });
    }

	public function ToString(){
		return $this->Capture();
    }

	/**
     * Get all the HTML document and elements of the Module
     * @return string
     */
	public function Get(){
		return join("",[$this->GetTitle(),$this->GetDescription(),$this->GetContent()]);
	}

	/**
     * Capture and return whole the Document contains Elements, Styles, Scripts, etc. completely.
     * @return string
     */
    public function Capture(){
        if($this->Capturable) return Convert::ToString(function(){
            $translate = \_::$CONFIG->AllowTranslate;
            $analyze = \_::$CONFIG->AllowTextAnalyzing;
            \_::$CONFIG->AllowTranslate = $translate && $this->AllowTranslate;
            \_::$CONFIG->AllowTextAnalyzing = $analyze && $this->AllowTextAnalyzing;
            yield $this->PreCapture();
            if($this->OneTimeStyle !== null){
                $this->OneTimeStyle = $this->OneTimeStyle?null:$this->OneTimeStyle;
                if($this->AllowDefaultStyles) yield $this->GetStyle();
                if(!isEmpty($this->Styles))
                    yield join(PHP_EOL,["<style>",Convert::ToString($this->Styles),"</style>"]);
            }
            yield $this->GetOpenTag();
            yield $this->Get();
            yield $this->GetCloseTag();
            if($this->OneTimeScript !== null){
                $this->OneTimeScript = $this->OneTimeScript?null:$this->OneTimeScript;
                if(!isEmpty($this->Scripts))
                    yield join(PHP_EOL,["<script>", Convert::ToString($this->Scripts),"</script>"]);
                if($this->AllowDefaultScripts) yield $this->GetScript();
            }
            yield $this->PostCapture();
            \_::$CONFIG->AllowTranslate = $translate;
            \_::$CONFIG->AllowTextAnalyzing = $analyze;
        });
        else {
            ob_start();
			$this->Draw();
			return ob_get_clean();
        }
    }
	/**
     * Capture and return whole the Document contains Elements except Styles and Scripts.
     * @return string
     */
    public function ReCapture(){
		if($this->Capturable) return Convert::ToString(function(){
            $translate = \_::$CONFIG->AllowTranslate;
            $analyze = \_::$CONFIG->AllowTextAnalyzing;
            \_::$CONFIG->AllowTranslate = $translate && $this->AllowTranslate;
            \_::$CONFIG->AllowTextAnalyzing = $analyze && $this->AllowTextAnalyzing;
            yield $this->PreCapture();
            yield $this->GetOpenTag();
            yield $this->Get();
            yield $this->GetCloseTag();
            yield $this->PostCapture();
            \_::$CONFIG->AllowTranslate = $translate;
            \_::$CONFIG->AllowTextAnalyzing = $analyze;
        });
        else {
            ob_start();
			$this->ReDraw();
			return ob_get_clean();
        }
    }

	/**
     * Echo all the HTML document and elements of the Module
     * @return bool
     */
	public function Echo(){
        $b = true;
        if($this->Capturable) echo $this->Get();
        else{
            $b = $this->EchoTitle() && $b;
            $b = $this->EchoDescription() && $b;
            $b = $this->EchoContent() && $b;
        }
        return $b;
	}

	/**
     * Echo whole the Document contains Elements, Styles, Scripts, etc. completely.
     */
	public function Draw(){
        if($this->Capturable) echo $this->Capture();
        else{
            $translate = \_::$CONFIG->AllowTranslate;
            $analyze = \_::$CONFIG->AllowTextAnalyzing;
            \_::$CONFIG->AllowTranslate = $translate && $this->AllowTranslate;
            \_::$CONFIG->AllowTextAnalyzing = $analyze && $this->AllowTextAnalyzing;
            $this->PreDraw();
            if($this->OneTimeStyle !== null){
                $this->OneTimeStyle = $this->OneTimeStyle?null:$this->OneTimeStyle;
                if($this->AllowDefaultStyles) $this->EchoStyle();
                if(!isEmpty($this->Styles))
                    echo join(PHP_EOL,["<style>",Convert::ToString($this->Styles),"</style>"]);
            }
            $this->EchoOpenTag();
            $this->Echo();
            $this->EchoCloseTag();
            if($this->OneTimeScript !== null){
                $this->OneTimeScript = $this->OneTimeScript?null:$this->OneTimeScript;
                if(!isEmpty($this->Scripts))
                    echo join(PHP_EOL,["<script>", Convert::ToString($this->Scripts),"</script>"]);
                if($this->AllowDefaultScripts) $this->EchoScript();
            }
            $this->PostDraw();
            \_::$CONFIG->AllowTranslate = $translate;
            \_::$CONFIG->AllowTextAnalyzing = $analyze;
        }
        return "";
	}
	/**
     * Echo whole the Document contains Elements except Styles and Scripts.
     */
	public function ReDraw(){
        if($this->Capturable) echo $this->ReCapture();
        else{
            $translate = \_::$CONFIG->AllowTranslate;
            $analyze = \_::$CONFIG->AllowTextAnalyzing;
            \_::$CONFIG->AllowTranslate = $translate && $this->AllowTranslate;
            \_::$CONFIG->AllowTextAnalyzing = $analyze && $this->AllowTextAnalyzing;
            $this->PreDraw();
            $this->EchoOpenTag();
            $this->Echo();
            $this->EchoCloseTag();
            $this->PostDraw();
            \_::$CONFIG->AllowTranslate = $translate;
            \_::$CONFIG->AllowTextAnalyzing = $analyze;
        }
        return "";
	}

}?>