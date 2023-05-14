<?php namespace MiMFa\Component;
/**
 * Components are the suite of different technologies that allow you to create reusable custom elements — with their functionality encapsulated away from the rest of your code — and utilize them in your web apps.
 * Components tend to be smaller bundles of code, often smaller than a full class. By their name, they tend to be a component of something larger.
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
 *@link https://github.com/mimfa/aseqbase/wiki/Components See the Documentation
 *@example FontAwesome.php
 */
class Component extends \Base{
	public function __construct(){
        parent::__construct();
    }
}
?>