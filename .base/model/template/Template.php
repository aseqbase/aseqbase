<?php
namespace MiMFa\Template;
use MiMFa\Library\Convert;
use MiMFa\Library\Html;
/**
 * Pre-designed layouts that allow you to arrange content onto a web page to quickly create a well-designed website.
 *
 * Guide for Documentations
 *
 *○ Use @var {bool, int, float, string, array<datatype>, etc.}: to indicate the variable or constant type. other useful type can be:
 *	enum-string: to indicate the legal string name for a variable
 *	class-string: to indicate the exist class name
 *	interface-string: to indicate the exist interface name
 *	lowercase-string, non-empty-string, non-empty-lowercase-string: to indicate a non empty string, lowercased or both at once
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
 *@link https://github.com/aseqbase/aseqbase/wiki/Template See the Documentation
 *@example Main.php
 */
class Template extends \Base{
    /**
     * The window title
     * @var string|null
     */
    public $WindowTitle = null;
    /**
     * The window icon
     * @var string|null
     */
    public $WindowLogo = null;

	/**
     * Leave null for default action or replace an action to change
	 * @var callable|null
	 */
	public $Initial = null;
	/**
     * Leave null for default action or replace an action to change
     * @var callable|null
     */
	public $Main = null;
	/**
     * Leave null for default action or replace an action to change
     * @var callable|null
     */
	public $Final = null;
	/**
     * Leave null for default action or replace an action to change
     * @var callable|null
     */
	public $Header = null;
	/**
     * Leave null for default action or replace an action to change
     * @var callable|null
     */
	public $Content = null;
	/**
     * Leave null for default action or replace an action to change
     * @var callable|null
     */
	public $Footer = null;

	public function Handler($received = null){
        ob_start();
		$this->Render();
        return ob_get_clean();
    }

	public function Render(){
		if(isValid($this->Initial)) \Res::Render($this->Initial);
		else $this->RenderInitial();
		if(isValid($this->Main)) \Res::Render($this->Main);
		else $this->RenderMain();
        if(isValid($this->Header)) \Res::Render($this->Header);
        else $this->RenderHeader();
        if(isValid($this->Content)) \Res::Render($this->Content);
        else $this->RenderContent();
        if(isValid($this->Footer)) \Res::Render($this->Footer);
        else $this->RenderFooter();
		if(isValid($this->Final)) \Res::Render($this->Final);
		else $this->RenderFinal();
	}

	public function RenderInitial(){
        region("initial");
        $title = $this->WindowTitle??[preg_replace("/[^A-Za-z0-9\/]+|(\.[A-z]+$)/","",\Req::$Direction)];
        \Res::Render(Html::Title(Convert::ToTitle(is_array($title)?[...$title,...[ \_::$Info->Name]]:$title)));
		\Res::Render(Html::Logo(getFullUrl($this->WindowLogo??\_::$Info->LogoPath)));
        \Res::Render(Html::Style("
        head, style, script, link, meta, title{
            display: none !important;
            visible: hidden !important;
            opacity: 0 !important;
        }
        html, body{
            font-family: var(--font-0), var(--font-3);
            font-size: var(--size-1);
            text-align: unset;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-3), var(--font-0);
            direction: var(--dir);
        }
        .button, .btn{
            font-family: var(--font-2), var(--font-5), var(--font-0);
        }
        .input{
            font-family: var(--font-1), var(--font-3), var(--font-0);
        }
        .tooltip {
            position: absolute;
            opacity: 0;
            font-family: var(--font-0);
            font-size: var(--size-0);
            font-weight: lighter;
            max-width: 70vw;
            min-width: 120px;
            width: max-content;
            background-color: var(--fore-color-0);
            color: var(--back-color-0);
            border: var(--border-1);
            border-radius: var(--radius-1);
            box-shadow: var(--shadow-5);
            padding: 9px 9px;
            z-index: -999;
            transition: var(--transition-0);
        }
        :not(html,head,body,style,script,link,meta,title):hover>.tooltip {
            opacity: 1;
            z-index: 999;
            transition: var(--transition-1) 2s;
        }
        :not(h1, h2, h3, h4, h5, h6 {
            line-height: 1.5em;
            direction: var(--dir);
        }

        big {
            font-size: 1.33em;
        }
        small {
            font-size: 0.75em;
        }
        sub, sup {
            line-height: 1em;
        }
        .icon {
            min-height: 1em;
            min-width: 1em;
        }
        :is(.button, .btn, .icon).success{
            background-color: var(--color-2);
            color: var(--fore-color-2);
            ".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
        }
        :is(.button, .btn, .icon).error{
            background-color: var(--color-1);
            color: var(--fore-color-2);
            ".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
        }
        :is(.button, .btn, .icon).message{
            background-color: var(--color-3);
            color: var(--fore-color-2);
            ".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
        }
        :is(.button, .btn, .icon).warning{
            background-color: var(--color-4);
            color: var(--fore-color-2);
            ".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
        }
        :is(.button, .btn, .icon).success:hover{
            background-color: ".\_::$Front->Color(1)."88;
            ".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
        }
        :is(.button, .btn, .icon).error:hover{
            background-color: ".\_::$Front->Color(0)."88;
            ".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
        }
        :is(.button, .btn, .icon).message:hover{
            background-color: ".\_::$Front->Color(2)."88;
            ".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
        }
        :is(.button, .btn, .icon).warning:hover{
            background-color: ".\_::$Front->Color(3)."88;
            ".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
        }
        "));
        foreach ($this as $key=>$value)
        	if(is_string($key)) \Res::Render(Html::Meta($key, Convert::ToString($value)));
            else \Res::Render($value);
    }
	public function RenderMain(){
        region("main");
    }
	public function RenderHeader(){
    }
	public function RenderContent(){
        foreach ($this->Children??[] as $key=>$value)
        	if(is_string($key)) \Res::Render(Html::Section($value,["Id" =>$key]));
            else \Res::Render($value);
    }
	public function RenderFooter(){
    }
	public function RenderFinal(){
        region("final");
    }
}