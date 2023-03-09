<?php namespace MiMFa\Module;
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
 *@see https://aseqbase.ir, https://github.com/mimfa/aseqbase
 *@link https://github.com/mimfa/aseqbase/wiki/Modules See the Documentation
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
     * Show this module when the screen size is one of the options below:
     * @var \ScreenSize
	 */
	public $ShowFromScreenSize = null;
	/**
     * Hide this module when the screen size is one of the options below:
     * @var \ScreenSize
     */
	public $HideFromScreenSize = null;
	/**
     * Visible this module when the screen size is one of the options below:
     * @var \ScreenSize
     */
	public $VisibleFromScreenSize = null;
	/**
     * Invisible this module when the screen size is one of the options below:
     * @var \ScreenSize
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

	public function __construct(){
        parent::__construct();
	}

	/**
     * Echo the Open tag of the element
     * @param string|null The specific TagName, set null for default
     */
	public function EchoOpenTag($tag=null){
		if(isValid($tag??$this->Tag)) echo "<".($tag??$this->Tag??"div")." ".$this->GetDefaultAttributes().">";
	}
	/**
     * Echo the Close tag of the element
     * @param string|null The specific TagName, set null for default
	 */
	public function EchoCloseTag($tag=null){
		if(isValid($tag??$this->Tag)) echo "</".($tag??$this->Tag??"div").">";
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
		(isValid($this->Attributes)?" ".(is_string($this->Attributes)? $this->Attributes:implode($this->Attributes," ")):"");
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
        return true;
    }

	/**
     * Echo the default module Scripts
     */
	public function EchoScript(){
        return true;
    }

	/**
	 * Echo all the HTML document and elements of the Module
	 */
	public function Echo(){
		$this->EchoTitle();
		$this->EchoDescription();
		$this->EchoContent();
        return true;
	}

	public function EchoTitle($attrs = null){
		if(isValid($this->Title)){
            echo (isValid($this->TitleTag)?"<".$this->TitleTag." $attrs>":"").__($this->Title).(isValid($this->TitleTag)?"</".$this->TitleTag.">":"");
            return true;
        }
        return false;
    }
	public function EchoDescription($attrs = null){
		if(isValid($this->Description)){
			echo (isValid($this->DescriptionTag)?"<".$this->DescriptionTag." $attrs>":"").__($this->Description).(isValid($this->DescriptionTag)?"</".$this->DescriptionTag.">":"");
            return true;
        }
        return false;
    }
	public function EchoContent($attrs = null){
		if(isValid($this->Content)){
			if(is_string($this->Content))
				echo (isValid($this->ContentTag)?"<".$this->ContentTag." $attrs>":"").__($this->Content).(isValid($this->ContentTag)?"</".$this->ContentTag.">":"");
            else ($this->Content)($attrs);
            return true;
        }
        return false;
    }

	/**
     * Echo whole the Document contains Elements, Styles, Scripts, etc. completely.
     */
	public function Draw(){
		$translate = \_::$CONFIG->AllowTranslate;
		$analyze = \_::$CONFIG->AllowTextAnalyzing;
		\_::$CONFIG->AllowTranslate = $translate && $this->AllowTranslate;
		\_::$CONFIG->AllowTextAnalyzing = $analyze && $this->AllowTextAnalyzing;
		$this->PreDraw();
		if($this->AllowDefaultStyles) $this->EchoStyle();
		if(isValid($this->Styles)) echo $this->Styles;
		$this->EchoOpenTag();
		$this->Echo();
		$this->EchoCloseTag();
		if(isValid($this->Scripts)) echo $this->Scripts;
		if($this->AllowDefaultScripts) $this->EchoScript();
		$this->PostDraw();
		\_::$CONFIG->AllowTranslate = $translate;
		\_::$CONFIG->AllowTextAnalyzing = $analyze;
	}
	/**
     * Echo whole the Document contains Elements except Styles and Scripts.
     */
	public function ReDraw(){
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

	/**
     * Echo in the Draw function before everything.
     */
	public function PreDraw(){ }
	/**
     * Echo in the Draw function after everything.
     */
	public function PostDraw(){ }
}?>