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
		if(isValid($this->Initial)) render($this->Initial);
		else $this->RenderInitial();
		if(isValid($this->Main)) render($this->Main);
		else $this->RenderMain();
        if(isValid($this->Header)) render($this->Header);
        else $this->RenderHeader();
        if(isValid($this->Content)) render($this->Content);
        else $this->RenderContent();
        if(isValid($this->Footer)) render($this->Footer);
        else $this->RenderFooter();
		if(isValid($this->Final)) render($this->Final);
		else $this->RenderFinal();
	}

	public function RenderInitial(){
        region("initial");
        $title = $this->WindowTitle??[preg_replace("/[^A-Za-z0-9\/]+|(\.[A-z]+$)/","",\_::$Address->Direction)];
        render(Html::Title(Convert::ToTitle(is_array($title)?[...$title,...[ \_::$Info->Name]]:$title)));
		render(Html::Logo(getFullUrl($this->WindowLogo??\_::$Info->LogoPath)));
        render(Html::Style("
        head, style, script, link, meta, title{
            display: none !important;
            visible: hidden !important;
            opacity: 0 !important;
        }
        html, body{
            font-family: var(--font), var(--font-special);
            font-size: var(--size-1);
            text-align: unset;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-special), var(--font);
            direction: var(--dir);
        }
        .tooltip {
            position: absolute;
            opacity: 0;
            font-family: var(--font);
            font-size: var(--size-0);
            font-weight: lighter;
            max-width: 70vw;
            min-width: 120px;
            width: max-content;
            background-color: var(--fore-color);
            color: var(--back-color);
            border: var(--border-1);
            border-radius: var(--radius-1);
            box-shadow: var(--shadow-5);
            padding: 9px 9px;
            z-index: -999;
            transition: var(--transition-0);
        }
        .result{
            font-size: var(--size-min);
            background-color: var(--back-color-special);
            border: var(--border-2);
            padding: calc(var(--size-min) / 2) var(--size-min);
            display: flex;
            align-content: center;
            align-items: center;
            gap: var(--size-min);
        }
        .result>.division{
            width: -webkit-fill-available;
        }
        .result.message{
            color: var(--color-blue);
        }
        .result.success{
            color: var(--color-green);
        }
        .result.error{
            color: var(--color-red);
        }
        .result.warning{
            color: var(--color-yellow);
        }
        :not(html,head,body,style,script,link,meta,title):hover>.tooltip {
            opacity: 1;
            z-index: 999;
            transition: var(--transition-1) 2s;
        }
        :not(h1, h2, h3, h4, h5, h6, p, .content) {
            line-height: 1.5em;
            direction: var(--dir);
        }
        h1{
            font-size: var(--size-5);
            text-align: center;
        }
        h2{
            font-size: var(--size-4);
            text-align: center;
        }
        h3{
            font-size: var(--size-3);
        }
        h4{
            font-size: var(--size-2);
        }
        h5{
            font-size: var(--size-1);
        }
        h6{
            font-size: var(--size-1);
            display: inline-block;
        }
        h6:before{
            display: block;
        }
            
        p, .content {
            line-height: 2em;
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
        picture {
            overflow: hidden;
            display: flex;
            align-content: center;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            flex-direction: column;
        }
        picture img {
            font-family: var(--font-special), var(--font);
            font-size: var(--size-2);
            max-width: 100%;
            max-height: 100%;
        }
        .icon {
            min-height: 1em;
            min-width: 1em;
            padding: 0;
        }
        button, .button{
            font-family: var(--font-output), var(--font-special-output), var(--font);
            text-align: center;
            background-color: unset;
            color: unset;
            overflow: unset;
            display: flex;
            flex-direction: column;
            align-content: center;
            justify-content: center;
            align-items: center;
            gap: var(--size-0);
            ".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
        }
        :is(button, .button):hover{
            font-weight: bold;
            ".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
        }
        :is(button, .button, .icon).success{
            background-color: var(--color-green);
            color: var(--color-white);
        }
        :is(button, .button, .icon).error{
            background-color: var(--color-red);
            color: var(--color-white);
        }
        :is(button, .button, .icon).message{
            background-color: var(--color-violet);
            color: var(--color-white);
        }
        :is(button, .button, .icon).warning{
            background-color: var(--color-yellow);
            color: var(--color-white);
        }
        .input{
            font-family: var(--font-input), var(--font-special-input), var(--font);
            background-color: var(--back-color-input);
            color: var(--fore-color-input);
            border: var(--border-1) var(--fore-color-input);
        }
        .input *{
            font-family: var(--font-input), var(--font-special-input), var(--font);
        }
        .input[type=file]:not(:disabled,[readonly]) {
            cursor: pointer;
        }
        .input:is(:disabled,[readonly]) {
            opacity: 0.7;
            cursor: not-allowed;
        }
        body>*:deactive {
            " . \MiMFa\Library\Style::UniversalProperty("filter", "grayscale(100)") . "
        }
        "));
        foreach ($this as $key=>$value)
        	if(is_string($key)) render(Html::Meta($key, Convert::ToString($value)));
            else render($value);
    }
	public function RenderMain(){
        region("main");
    }
	public function RenderHeader(){
    }
	public function RenderContent(){
        foreach ($this->Children??[] as $key=>$value)
        	if(is_string($key)) render(Html::Section($value, ["Id" =>$key]));
            else render($value);
    }
	public function RenderFooter(){
    }
	public function RenderFinal(){
        region("final");
    }
}