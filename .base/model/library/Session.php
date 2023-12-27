<?php
namespace MiMFa\Library;
require_once "HashCrypt.php";
require_once "DataBase.php";
/**
 * A simple library to Session management
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#session See the Library Documentation
*/
class Session
{
	public static $Time = 86400;
	public static $Separator = "\\";

	public static function Start(){
        self::StartSecure();
		if(is_null(self::GetID()) && !is_null(\_::$CONFIG)){
			self::SetID(\_::$CONFIG->PublicPrefix);
            self::Set("IP", getClientIP());
			return true;
        }
        return false;
	}
	public static function ReStart(){
		self::Stop();
		return self::Start();
	}
	public static function Stop(){
		self::FlushData();
		self::FlushCookie();
		self::FlushSecure();
		self::FlushID();
	}

	public static function GetDataBaseName(){
		return \_::$CONFIG->DataBasePrefix."Session";
    }

	public static function SetID($id){
		return $_SESSION["SESSION_ID"] = $id;
    }
	public static function GetID(){
		return getValid($_SESSION, "SESSION_ID", null);
    }
	public static function PopID(){
		$val = self::GetID();
		self::ForgetID();
		return $val;
	}
	public static function HasID(){
		return isset($_SESSION["SESSION_ID"]);
    }
	public static function ForgetID(){
		unset($_SESSION["SESSION_ID"]);
    }
	public static function FlushID(){
		unset($_SESSION["SESSION_ID"]);
	}

	public static function Set($key,$val){
		if(\_::$CONFIG->ClientSession) return self::SetCookie($key,$val);
		else return self::SetData($key,$val);
    }
	public static function Get($key){
		if(\_::$CONFIG->ClientSession) return self::GetCookie($key);
		return self::GetData($key);
	}
	public static function Pop($key){
		$val = self::Get($key);
		self::Forget($key);
		return $val;
	}
	public static function Has($key){
		if(\_::$CONFIG->ClientSession) return self::GetCookie($key)!= null;
		return self::HasData($key);
	}
	public static function Forget($key){
		if(\_::$CONFIG->ClientSession) return self::ForgetCookie($key);
		return self::ForgetData($key);
	}
	public static function Flush(){
		if(\_::$CONFIG->ClientSession) return self::FlushCookie();
		return self::FlushData();
	}

	public static function SetData($key,$val){
		return DataBase::DoReplace(self::GetDataBaseName(),null,[':Key'=>self::ToCipherKey($key),':Value'=>self::ToCipherValue($val),':IP'=>getClientIP()]);
    }
	public static function GetData($key){
		return self::ToPlainValue(DataBase::DoSelectValue(self::GetDataBaseName(),"Value","`Key`=:Key",[':Key'=>self::ToCipherKey($key)]));
	}
	public static function PopData($key){
		$val = self::GetData($key);
		self::ForgetData($key);
		return $val;
	}
	public static function HasData($key){
		return DataBase::Exists(self::GetDataBaseName(),null,"`Key`=:Key",[':Key'=>self::ToCipherKey($key)]);
	}
	public static function ForgetData($key){
		return DataBase::DoDelete(self::GetDataBaseName(),"`Key`=:Key",[':Key'=>self::ToCipherKey($key)]);
	}
	public static function FlushData(){
		return DataBase::DoDelete(self::GetDataBaseName(),"`Key` LIKE '".self::GetID().self::$Separator."%'");
	}

	public static function SetCookie($key,$val){
		if($val == null) return false;
		return setcookie(self::ToCipherKey($key), self::ToCipherValue($val), time() + self::$Time,"/");
	}
	public static function GetCookie($key){
		$key = self::ToCipherKey($key);
		if(isset($_COOKIE[$key])) return self::ToPlainValue($_COOKIE[$key]);
		else return null;
	}
	public static function PopCookie($key){
		$val = self::GetCookie($key);
		self::ForgetCookie($key);
		return $val;
	}
	public static function HasCookie($key){
		return !is_null(self::GetCookie($key));
	}
	public static function ForgetCookie($key){
		$key = self::ToCipherKey($key);
		unset($_COOKIE[$key]);
		return setcookie($key, "", time() - self::$Time,"/");
	}
	public static function FlushCookie(){
		$sk = self::GetID().self::$Separator;
		foreach($_COOKIE as $key => $val)
			if(startsWith($key,$sk)){
                unset($_COOKIE[$key]);
                setcookie($key, "", time() - self::$Time,"/");
            }
	}


	public static function StartSecure(){
        if(session_id() == "")
			session_start([
				'cookie_lifetime' => self::$Time
			]);
        return session_id() != "";
    }
	public static function SetSecure($key,$val){
		return $_SESSION[self::ToKey($key)] = self::Encrypt($val);
    }
	public static function GetSecure($key){
		$key = self::ToKey($key);
		if(isset($_SESSION[$key])) return self::Decrypt($_SESSION[$key]);
		else return null;
    }
	public static function PopSecure($key){
		$key = self::ToKey($key);
		$val = self::GetSecure($key);
		self::ForgetSecure($key);
		return $val;
	}
	public static function HasSecure($key){
		return isset($_SESSION[self::ToKey($key)]);
	}
	public static function ForgetSecure($key){
		unset($_SESSION[self::ToKey($key)]);
    }
	public static function FlushSecure(){
		$id = self::GetID().self::$Separator;
		foreach($_SESSION as $key => $val)
			if(startsWith($key, $id))
				unset($_SESSION[$key]);
	}


	protected static function ToKey($key){
		return substr(self::GetID().self::$Separator.$key,0,65);
    }
	protected static function ToCipherKey($key){
		if(\_::$CONFIG->EncryptSessionKey) return self::ToKey(self::Encrypt($key));
		else return self::ToKey($key);
    }
	protected static function ToPlainKey($key){
		$ks = explode(self::$Separator,$key);
		if(\_::$CONFIG->EncryptSessionKey) return self::Decrypt(end($ks));
		return end($ks);
    }
	protected static function ToCipherValue($value){
		if(\_::$CONFIG->EncryptSessionValue) return self::Encrypt($value);
		else return $value;
    }
	protected static function ToPlainValue($value){
		if(\_::$CONFIG->EncryptSessionValue) return self::Decrypt($value);
		else return $value;
    }

	public static function Encrypt($plain){
		if(is_null($plain)) return null;
		if(empty($plain)) return $plain;
		return HashCrypt::Encrypt($plain,\_::$CONFIG->SecretKey, true);
    }
	public static function Decrypt($cipher){
		if(is_null($cipher)) return null;
		if(empty($cipher)) return $cipher;
		try{
            return HashCrypt::Decrypt($cipher,\_::$CONFIG->SecretKey, true);
        }
        catch (\Exception $exception)
        {
			return null;
        }
    }

	public function __construct() {
        if(!$this->isAlive())
			session_start([
				'cookie_lifetime' => self::$Time
			]);
    }
    /**
    *    Stores datas in the session.
    *    Example: $instance->foo = 'bar';
    *
    *    @param    $name    Name of the datas.
    *    @param    $value    Your datas.
    *    @return    void
    **/
    public function __set( $name , $value )
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
    public function __get( $name )
    {
        if ( isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }
    }
    public function __isset( $name )
    {
        return isset($_SESSION[$name]);
    }
    public function __unset( $name )
    {
        unset( $_SESSION[$name] );
    }
    /**
    *    Destroys the current session.
    *
    *    @return    bool    TRUE is session has been deleted, else FALSE.
    **/
    public function destroy()
    {
        if ( $this->isAlive() )
        {
            session_destroy();
            unset( $_SESSION );
            return true;
        }
        return false;
    }
	public function isAlive()
    {
		return session_id() != "";
    }
}

?>