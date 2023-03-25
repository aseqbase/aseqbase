<?php
namespace MiMFa\Library;
require_once "HashCrypt.php";
require_once "DataBase.php";
/**
 * A simple library to Session management
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/mimfa/aseqbase
 *@link https://github.com/mimfa/aseqbase/wiki/Libraries#session See the Library Documentation
*/
class Session
{
	public static $Time = 86400;

	public static function Start(){
        session_start();
		if(is_null(self::GetID())){
            self::Set("IP",getClientIP());
			return true;
        }
        return false;
	}

	public static function GetDataBaseName(){ return \_::$CONFIG->DataBasePrefix."Session";}

	public static function PublicID(){
		return getValid($_SESSION,"SESSION_ID");
    }
	public static function PrivateID(){
		return self::Decrypt(self::PublicID());
    }
	protected static function GetID(){
		return getValid($_SESSION,"SESSION_ID");
    }
	protected static function SetID($id){
		return $_SESSION["SESSION_ID"] = self::Encrypt($id);
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
		$id = self::GetID()??(DataBase::GetMax(self:: GetDataBaseName())??0)+1;
		return DataBase::DoReplace(self:: GetDataBaseName(),null,[':ID'=>$id, ':Key'=>"$id-".self::FromKey($key),':Value'=>self::FromValue($val),':IP'=>getClientIP()]);
    }
	public static function GetData($key){
		return self::ToValue(DataBase::DoSelectValue(self:: GetDataBaseName(),"Value","`Key`=:Key",[':Key'=>self::FromKey($key)]));
	}
	public static function PopData($key){
		$val = self::GetData($key);
		self::ForgetData($key);
		return $val;
	}
	public static function HasData($key){
		return DataBase::Exists(self:: GetDataBaseName(),null,"`Key`=:Key",[':Key'=>self::FromKey($key)]);
	}
	public static function ForgetData($key){
		return DataBase::DoDelete(self:: GetDataBaseName(),null,"`Key`=:Key",[':Key'=>self::FromKey($key)]);
	}
	public static function FlushData(){
		return DataBase::DoDelete(self:: GetDataBaseName(),"`Key` LIKE '".self::GetID()."-%'");
	}

	public static function SetCookie($key,$val){
		return setcookie(self::FromKey($key),self::FromValue($val), time() + self::$Time,"/");
	}
	public static function GetCookie($key){
		$key = self::FromKey($key);
		if(isset($_COOKIE[$key])) return self::ToValue($_COOKIE[$key]);
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
		$key = self::FromKey($key);
		unset($_COOKIE[$key]);
		return setcookie($key, "", time() - self::$Time,"/");
	}
	public static function FlushCookie(){
		$sk = self::GetID()."-";
		foreach($_COOKIE as $key => $val)
			if(startsWith($key,$sk)){
                unset($_COOKIE[$key]);
                return setcookie($key, "", time() - self::$Time,"/");
            }
	}


	public static function SetSecure($key,$val){
		return $_SESSION[self::Encrypt($key)] = self::Encrypt($val);
    }
	public static function GetSecure($key){
		$key = self::Encrypt($key);
		if(isset($_SESSION[$key])) return self::Decrypt($_SESSION[$key]);
		else return null;
    }
	public static function PopSecure($key){
		$val = self::GetSecure($key);
		self::ForgetSecure($key);
		return $val;
	}
	public static function HasSecure($key){
		return isset($_SESSION[self::Encrypt($key)]);
	}
	public static function ForgetSecure($key){
		unset($_SESSION[self::Encrypt($key)]);
    }
	public static function FlushSecure(){
		foreach($_SESSION as $key => $val)
			self::ForgetSecure($key);
	}


	protected static function FromKey($key){
		if(\_::$CONFIG->EncryptSessionKey) return self::GetID()."-".self::Encrypt($key);
		else return self::GetID()."-$key";
    }
	protected static function ToKey($key){
		if(\_::$CONFIG->EncryptSessionKey) return substr(self::Decrypt($key),65);
		else return substr($key,65);
    }
	protected static function FromValue($value){
		if(\_::$CONFIG->EncryptSessionValue) return self::Encrypt($value);
		else return $value;
    }
	protected static function ToValue($value){
		if(\_::$CONFIG->EncryptSessionValue) return self::Decrypt($value);
		else return $value;
    }

	public static function Encrypt($plain){
		if(is_null($plain)) return null;
		return HashCrypt::Encrypt($plain,\_::$CONFIG->SecretKey,true);
    }
	public static function Decrypt($cipher){
		if(is_null($cipher)) return null;
		return HashCrypt::Decrypt($cipher,\_::$CONFIG->SecretKey,true);
    }

}
?>