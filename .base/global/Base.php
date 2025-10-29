<?php
library("Revise");
/**
 *Guide for Documentations
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
 *@link https://github.com/aseqbase/aseqbase/wiki/Structures See the Structures Documentation
 */
class Base extends \ArrayObject
{
	/**
	 * The main name of the object
	 * @var string|null
	 */
	public $Name = null;
	/**
	 * The main router of the object
	 * @var Router
	 */
	public $Router = null;
	/**
	 * Additional Children of the object
	 * @internal
	 * @field collection
	 * @var array<string|Base|callable>|Base|callable|string
	 * @medium
	 */
	public $Children = null;
	/**
	 * This object is convertable to string and able to embed anywhere or not
	 * @internal
	 * @var bool|null
	 */
	public $Visual = true;
	public $Rendered = 0;
	public $Handled = 0;

	function __construct($setDefaults = true, $setRevises = true)
	{
		if ($setDefaults)
			$this->Set_Defaults();
		if ($setRevises)
			\MiMFa\Library\Revise::Load($this);
		$this->Router = (new Router())
			->Get(fn(&$router) => $this->Get())
			->Post(fn(&$router) => $this->Post())
			->Put(fn(&$router) => $this->Put())
			->File(fn(&$router) => $this->File())
			->Patch(fn(&$router) => $this->Patch())
			->Delete(fn(&$router) => $this->Delete())
			->Stream(fn(&$router) => $this->Stream())
			->Internal(fn(&$router) => $this->Internal())
			->External(fn(&$router) => $this->External())
			->Default(fn(&$router) => $this->Default());
	}

	public function New($setDefaults = true, $setRevises = true)
	{
		if ($setDefaults)
			$this->Set_Defaults();
		if ($setRevises)
			\MiMFa\Library\Revise::Load($this);
		return $this;
	}

	public function Set_Defaults()
	{
		$this->Name = \_::$Config->EncryptNames ? (substr($this->Get_Namespace(), 0, 1) . RandomString(10)) : ($this->Name ?? $this->get_className()) . "_" . $this->Get_Namespace();
		return $this;
	}

	public function Get_Class()
	{
		return get_class($this);
	}
	public function Get_ClassName()
	{
		$v = explode('\\', $this->Get_Class());
		return end($v);
	}

	public function Get_Namespaces()
	{
		$v = explode('\\', $this->Get_Class());
		unset($v[count($v) - 1]);
		return $v;
	}
	public function Get_Namespace()
	{
		$v = $this->Get_Namespaces();
		return end($v);
	}

	public function AddChild($child)
	{
		if (is_null($this->Children))
			$this->Children = array();
		//if(!is_null($child)) $child = is_subclass_of($child,"Base")? function()use($child){ $child->ToString(); }:$child;
		if (is_string($this->Children))
			$this->Children .= MiMFa\Library\Convert::ToString($child);
		else
			array_push($this->Children, $child);
		return true;
	}
	public function RemoveChild($child)
	{
		if (is_null($this->Children))
			$this->Children = array();
		//if(!is_null($child)) $child = is_subclass_of($child,"Base")? function() use($child){ $child->ToString(); }:$child;
		if (is_string($this->Children)) {
			$this->Children = str_replace(MiMFa\Library\Convert::ToString($child), "", $this->Children);
			return true;
		} else {
			$key = array_search($child, $this->Children);
			if ($key) {
				unset($this->Children[$key]);
				return true;
			}
		}
		return false;
	}

	public function Get()
	{
		return $this->Handler(receiveGet());
	}
	public function Post()
	{
		return $this->Handler(receivePost());
	}
	public function Put()
	{
		return $this->Handler(receivePut());
	}
	public function File()
	{
		return $this->Handler(receiveFile());
	}
	public function Patch()
	{
		return $this->Handler(receivePatch());
	}
	public function Delete()
	{
		return $this->Handler(receiveDelete());
	}
	public function Stream()
	{
		return $this->Handler(receiveStream());
	}
	public function Internal()
	{
		return $this->Handler(receiveInternal());
	}
	public function External()
	{
		return $this->Handler(receiveExternal());
	}
	public function Default()
	{
		return $this->Handler(receive());
	}

	public function Handler($received = null)
	{
		return MiMFa\Library\Html::Convert($received ?? $this->Children) . MiMFa\Library\Html::Convert($this->__toArray());
	}

	/**
	 * Returns whole the contents
	 */
	public function Handle()
	{
		$this->Router->Handle();
		$this->Handled++;
		return $this->Router->Result;
	}

	/**
	 * Echos and returns whole the contents
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
		$output = $this->Render();
		return ob_get_clean() ?? $output;
	}

	public function __toString()
	{
		return $this->ToString();
	}
	public function __toArray()
	{
		$arr = [];
		foreach ($this as $key => $value)
			$arr[$key] = $value;
		return $arr;
	}
	public function __get($name)
	{
		if (is_int($name))
			return $this->offsetGet($name);
		if (is_string($name)) {
			$n = $this->__getName($name);
			if (method_exists($this, $n))
				return $this->$n();
			if (property_exists($this, $n))
				return $this->$n;
		}
		return get($this, $name);
	}
	public function __set($name, $value)
	{
		if (is_int($name))
			return $this->offsetSet($name, $value);
		if (is_string($name)) {
			$n = $this->__getName($name);
			if (method_exists($this, $n))
				return $this->$n($value);
			if (property_exists($this, $n))
				return $this->$n = $value;
		}
		return set($this, [$name => $value]);
	}
	private function __getName($name)
	{
		return preg_replace("/\W+/", "", strToProper($name));
	}
}