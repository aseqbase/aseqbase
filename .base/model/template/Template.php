<?php
namespace MiMFa\Template;
use MiMFa\Library\Convert;
use MiMFa\Library\HTML;
/**
 * Pre-designed layouts that allow you to arrange content onto a web page to quickly create a well-designed website.
 *
 *Guide for Documentations
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

	public function Draw(){
		if(isValid($this->Initial)) echo Convert::ToString($this->Initial);
		else $this->DrawInitial();
		if(isValid($this->Main)) echo Convert::ToString($this->Main);
		else $this->DrawMain();
        if(isValid($this->Header)) echo Convert::ToString($this->Header);
        else $this->DrawHeader();
        if(isValid($this->Content)) echo Convert::ToString($this->Content);
        else $this->DrawContent();
        if(isValid($this->Footer)) echo Convert::ToString($this->Footer);
        else $this->DrawFooter();
		if(isValid($this->Final)) echo Convert::ToString($this->Final);
		else $this->DrawFinal();
	}

	public function DrawInitial(){
        REGION("initial");?>
		<title>
            <?php
            $title = $this->WindowTitle??[\_::$DIRECTION];
            echo __(Convert::ToTitle(is_array($title)?[...$title,...[ \_::$INFO->Name]]:$title),styling:false); ?>
		</title>
		<link rel="icon" href="<?php echo getFullUrl($this->WindowLogo??\_::$INFO->LogoPath); ?>" />
	<?php
        echo HTML::Style("
        .tooltip {
            font-family: inherit;
            font-size: var(--Size-0);
            font-weight: lighter;
            max-width: 70vw;
            min-width: 120px;
            width: max-content;
            background-color: var(--ForeColor-0);
            color: var(--BackColor-0);
            border: var(--Border-1);
            border-radius: var(--Radius-1);
            box-shadow: var(--Shadow-4);
            padding: 9px 9px;
            transition: var(--Transition-0);
            z-index: -999;
        }
        *:hover>.tooltip {
            opacity: 1;
            transition: var(--Transition-1) 2s;
            z-index: 999;
        }");
    }
	public function DrawMain(){
        REGION("main");
    }
	public function DrawHeader(){
    }
	public function DrawContent(){
    }
	public function DrawFooter(){
    }
	public function DrawFinal(){
        REGION("final");
    }
}
?>