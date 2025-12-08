<?php
namespace MiMFa\Library;
require_once "DataTable.php";
require_once "Cryptograph.php";
/**
 * A simple library to Session management
 * @copyright All rights are reserved for MiMFa Development Group
 * @author Mohammad Fathi
 * @see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 * @link https://github.com/aseqbase/aseqbase/wiki/Libraries#session See the Library Documentation
 */
class Session
{
	public DataTable $DataTable;
	public Cryptograph $Cryptograph;
	public $Time = 86400;
	public $Separator = "\\";
	/**
	 * Allow to set sessions on the client side (false for default)
	 * @var bool
	 */
	public $AccessibleData = true;
	/**
	 * Encrypt all session keys (false for default)
	 * @var bool
	 */
	public $EncryptKey = false;
	/**
	 * Encrypt all session values (true for default)
	 * @var bool
	 */
	public $EncryptValue = true;

	public function __construct(DataTable $dataTable, Cryptograph $cryptograph)
	{
		$this->DataTable = $dataTable;
		$this->Cryptograph = $cryptograph;
		if (!$this->isAlive())
			$this->Start();
	}
	/**
	 * Stores datas in the session.
	 * Example: $instance->foo = 'bar';
	 *
	 * @param    $name    Name of the datas.
	 * @param    $value    Your datas.
	 * @return    void
	 **/
	public function __set($name, $value)
	{
		$_SESSION[$name] = $value;
	}
	/**
	 * Gets datas from the session.
	 * Example: echo $instance->foo;
	 *
	 * @param    $name    Name of the datas to get.
	 * @return    mixed    Datas stored in session.
	 **/
	public function __get($name)
	{
		if (isset($_SESSION[$name])) {
			return $_SESSION[$name];
		}
	}
	public function __isset($name)
	{
		return isset($_SESSION[$name]);
	}
	public function __unset($name)
	{
		unset($_SESSION[$name]);
	}
	/**
	 * Destroys the current session.
	 *
	 * @return    bool    TRUE is session has been deleted, else FALSE.
	 **/
	public function destroy()
	{
		if ($this->isAlive()) {
			session_destroy();
			unset($_SESSION);
			return true;
		}
		return false;
	}
	public function isAlive()
	{
		return session_id() != "";
	}

	public function Start()
	{
		$this->StartSecure();
		if (is_null($this->GetId())) {
			$this->SetId(getClientCode(\_::$Address->Name));
			$this->Set("Ip", getClientIp());
			return true;
		}
		return false;
	}
	public function Restart()
	{
		$this->Stop();
		return $this->Start();
	}
	public function Stop()
	{
		$this->ClearData();
		$this->ClearCookie();
		$this->ClearSecure();
		$this->ClearId();
	}

	public function SetId($id)
	{
		return $_SESSION["SESSION_ID"] = $id;
	}
	public function GetId()
	{
		return takeValid($_SESSION, "SESSION_ID", null);
	}
	public function PopId()
	{
		$val = $this->GetId();
		unset($_SESSION["SESSION_ID"]);
		return $val;
	}
	public function HasId()
	{
		return isset($_SESSION["SESSION_ID"]);
	}
	public function ClearId()
	{
		unset($_SESSION["SESSION_ID"]);
	}

	/**
	 * To SetCookie if $this->AccessibleData is true, SetData otherwise
	 * @param mixed $key
	 * @param mixed $val
	 * @return bool|int
	 */
	public function Set($key, $val)
	{
		if ($this->AccessibleData)
			return $this->SetCookie($key, $val);
		else
			return $this->SetData($key, $val);
	}
	/**
	 * Get the sat Cookie or Data (Cookie if $this->AccessibleData is true, Data otherwise)
	 * @param mixed $key
	 */
	public function Get($key)
	{
		if ($this->AccessibleData)
			return $this->GetCookie($key);
		return $this->GetData($key);
	}
	/**
	 * Pop the sat Cookie or Data (Cookie if $this->AccessibleData is true, Data otherwise)
	 * @param mixed $key
	 */
	public function Pop($key)
	{
		if ($this->AccessibleData)
			return $this->PopCookie($key);
		else return $this->PopData($key);
	}
	/**
	 * Check if the Cookie or Data is set or not (Cookie if $this->AccessibleData is true, Data otherwise)
	 * @param mixed $key
	 * @return bool
	 */
	public function Has($key)
	{
		if ($this->AccessibleData)
			return $this->GetCookie($key) != null;
		return $this->HasData($key);
	}
	/**
	 * Clear the Cookies or Data (Cookie if $this->AccessibleData is true, Data otherwise)
	 * @return bool|int
	 */
	public function Clear()
	{
		if ($this->AccessibleData) {
			$this->ClearCookie();
			return true;
		}
		return $this->ClearData();
	}

	/**
	 * To set data by a secure and Server side way to store
	 * @param mixed $key
	 * @param mixed $val
	 * @return bool|int
	 */
	public function SetData($key, $val)
	{
		return $this->DataTable->Replace([':Key' => $this->ToCipherKey($key), ':Value' => $this->ToCipherValue($val), ':Ip' => getClientIp()]);
	}
	/**
	 * To get data stored on a secure and Server side way
	 * @param mixed $key
	 */
	public function GetData($key)
	{
		return $this->ToPlainValue($this->DataTable->SelectValue("Value", "`Key`=:Key", [':Key' => $this->ToCipherKey($key)]));
	}
	/**
	 * To pop data stored on a secure and Server side way
	 * @param mixed $key
	 */
	public function PopData($key)
	{
		$key = $this->ToCipherKey($key);
		$val = $this->ToPlainValue($this->DataTable->SelectValue("Value", "`Key`=:Key", [':Key' => $key]));
		$this->DataTable->Delete("`Key`=:Key", [':Key' => $key]);
		return $val;
	}
	/**
	 * To check existence of the data stored on a secure and Server side way
	 * @param mixed $key
	 * @return bool
	 */
	public function HasData($key)
	{
		return $this->DataTable->Exists("`Key`=:Key", [':Key' => $this->ToCipherKey($key)]);
	}
	/**
	 * To clear all data stored on a secure and Server side way
	 * @return bool|int
	 */
	public function ClearData()
	{
		return $this->DataTable->Delete("`Key` LIKE '" . $this->GetId() . $this->Separator . "%'");
	}

	/**
	 * To set cookie on the client side
	 * @param mixed $key
	 * @param mixed $val
	 * @return bool
	 */
	public function SetCookie($key, $val)
	{
		if ($val == null)
			return false;
		return setcookie($this->ToCipherKey($key), $this->ToCipherValue($val), time() + $this->Time, "/");
	}
	/**
	 * To get cookie data stored on the client side
	 * @param mixed $key
	 */
	public function GetCookie($key)
	{
		$key = $this->ToCipherKey($key);
		if (isset($_COOKIE[$key]))
			return $this->ToPlainValue($_COOKIE[$key]);
		else
			return null;
	}
	/**
	 * To pop cookie data stored on the client side
	 * @param mixed $key
	 */
	public function PopCookie($key)
	{
		$key = $this->ToCipherKey($key);
		$val = null;
		if (isset($_COOKIE[$key]))
			$val = $this->ToPlainValue($_COOKIE[$key]);
		unset($_COOKIE[$key]);
		setcookie($key, "", time() - $this->Time, "/");
		return $val;
	}
	/**
	 * To check existence of a cookie data stored on the client side
	 * @param mixed $key
	 * @return bool
	 */
	public function HasCookie($key)
	{
		return !is_null($this->GetCookie($key));
	}
	/**
	 * To clear all cookies stored on the client side
	 * @return void
	 */
	public function ClearCookie()
	{
		$sk = $this->GetId() . $this->Separator;
		foreach ($_COOKIE as $key => $val)
			if (startsWith($key, $sk)) {
				unset($_COOKIE[$key]);
				setcookie($key, "", time() - $this->Time, "/");
			}
	}


	public function StartSecure()
	{
		if (session_id() == "")
			session_start([
				'cookie_lifetime' => $this->Time
			]);
		return session_id() != "";
	}
	/**
	 * To set session on the server side
	 * @param mixed $key
	 * @param mixed $val
	 */
	public function SetSecure($key, $val)
	{
		return $_SESSION[$this->ToKey($key)] = $this->Encrypt($val);
	}
	/**
	 * To get session stored on the server side
	 * @param mixed $key
	 */
	public function GetSecure($key)
	{
		$key = $this->ToKey($key);
		if (isset($_SESSION[$key]))
			return $this->Decrypt($_SESSION[$key]);
		else
			return null;
	}
	/**
	 * To pop session stored on the server side
	 * @param mixed $key
	 */
	public function PopSecure($key)
	{
		$key = $this->ToKey($key);
		$val = null;
		if (isset($_SESSION[$key]))
			$val = $this->Decrypt($_SESSION[$key]);
		unset($_SESSION[$key]);
		return $val;
	}
	/**
	 * To check if the session stored on the server side or not
	 * @param mixed $key
	 * @return bool
	 */
	public function HasSecure($key)
	{
		return isset($_SESSION[$this->ToKey($key)]);
	}
	/**
	 * To clear all related session stored on the server side
	 * @return void
	 */
	public function ClearSecure()
	{
		$id = $this->GetId() . $this->Separator;
		foreach ($_SESSION as $key => $val)
			if (startsWith($key, $id))
				unset($_SESSION[$key]);
	}


	protected function ToKey($key)
	{
		return substr($this->GetId() . $this->Separator . $key, 0, 65);
	}
	protected function ToCipherKey($key)
	{
		if ($this->EncryptKey)
			return $this->ToKey($this->Encrypt($key));
		else
			return $this->ToKey($key);
	}
	protected function ToPlainKey($key)
	{
		$ks = explode($this->Separator, $key);
		if ($this->EncryptKey)
			return $this->Decrypt(end($ks));
		return end($ks);
	}
	protected function ToCipherValue($value)
	{
		if ($this->EncryptValue)
			return $this->Encrypt($value);
		else
			return $value;
	}
	protected function ToPlainValue($value)
	{
		if ($this->EncryptValue)
			return $this->Decrypt($value);
		else
			return $value;
	}

	public function Encrypt($plain)
	{
		if (is_null($plain))
			return null;
		if (empty($plain))
			return $plain;
		return $this->Cryptograph->Encrypt($plain, $this->SecretKey, true);
	}
	public function Decrypt($cipher)
	{
		if (is_null($cipher))
			return null;
		if (empty($cipher))
			return $cipher;
		try {
			return $this->Cryptograph->Decrypt($cipher, $this->SecretKey, true);
		} catch (\Exception $exception) {
			return null;
		}
	}
}