<?php
LIBRARY("Revise");
/**
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
 *@link https://github.com/aseqbase/aseqbase/wiki/Structures See the Structures Documentation
 */
class Base{
	/**
	 * The main name of the object
	 * @var string|null
	 */
	public $Name = null;
	/**
     * Additional Children of the object
     * @field collection
     * @var array<string|Base|callable>|Base|callable|string
     * @medium
     */
	public $Children = null;
	/**
     * This object is convertable to string and able to embedd anywhere or not
     * @internal
     * @var bool|null
     */
	public $Capturable = null;

	function __construct($setDefaults = true, $setRevises = true){
		if($setDefaults) $this->Set_Defaults();
        if($setRevises) \MiMFa\Library\Revise::Load($this);
    }

	public function Set_Defaults(){
		$this->Name = \_::$CONFIG->EncryptNames?(substr($this->Get_Namespace(),0,1).RandomString(10)):($this->Name??$this->get_className())."_".$this->Get_Namespace();
	}

	public function Get_Class(){
		return get_class($this);
	}
	public function Get_ClassName(){
		$v = explode('\\', $this->Get_Class());
		return end($v);
	}

	public function Get_Namespaces(){
		$v = explode('\\', $this->Get_Class());
		unset($v[count($v)-1]);
		return $v;
	}
	public function Get_Namespace(){
		$v = $this->Get_Namespaces();
		return end($v);
	}

	public function AddChild($child){
		if(is_null($this->Children)) $this->Children = array();
		//if(!is_null($child)) $child = is_subclass_of($child,"Base")? function()use($child){ $child->ToString(); }:$child;
		if(is_string($this->Children)) $this->Children .= MiMFa\Library\Convert::ToString($child);
		else array_push($this->Children, $child);
        return true;
    }
	public function RemoveChild($child){
		if(is_null($this->Children)) $this->Children = array();
		//if(!is_null($child)) $child = is_subclass_of($child,"Base")? function() use($child){ $child->ToString(); }:$child;
		if(is_string($this->Children)){
            $this->Children = str_replace(MiMFa\Library\Convert::ToString($child),"",$this->Children);
            return true;
        }else{
            $key = array_search($child, $this->Children);
            if($key){
                unset($this->Children[$key]);
                return true;
            }
        }
		return false;
    }

	public function Get(){
		return MiMFa\Library\Convert::ToString($this->Children);
    }

	public function Capture(){
		return $this->Get();
    }
	/**
     * Get in the Capture function before everything.
     */
	public function PreCapture(){ return null; }
	/**
     * Get in the Capture function after everything.
     */
	public function PostCapture(){ return null; }


	public function ToString(){
		return $this->Capturable?$this->Capture():$this->Draw();
    }
	public function __toString(){
		return $this->Capturable?$this->Capture():$this->Draw();
    }


	/**
	 * Echo all the HTML document and elements of the Object
	 */
	public function Echo(){
		echo $this->Get();
    }

	/**
     * Echo whole the Document contains Elements, Styles, Scripts, etc. completely.
     */
	public function Draw(){
        if($this->Capturable) echo $this->Capture();
		else{
            $this->PreDraw();
            $this->Echo();
            $this->PostDraw();
        }
		return "";
    }
	/**
     * Echo whole the Document contains Elements, Styles, Scripts, etc. completely, as a new Object.
     */
	public function NewDraw(){
		$this->Set_Defaults();
		$this->Draw();
    }
	/**
     * Echo in the Draw function before everything.
     */
	public function PreDraw(){ echo $this->PreCapture(); }
	/**
     * Echo in the Draw function after everything.
     */
	public function PostDraw(){ echo $this->PostCapture();  }
}
?>