<?php namespace MiMFa\Library;
require_once "Base.php";
require_once "DataBase.php";

class Session
{
	public static $Time = 86400;
	public static $Prefix = "session_";

	public static function start(){
		self::flush();
		self::flush(true);
	}

	public static function put($key,$val,$database = false){
		$ip = GetClientIP();
		if(!$database) return self::putcookie($key,$val);
		else return DataBase::insert("INSERT INTO sessions (id,user_agent,payload,ip_address) VALUES(:id,:key,:val,:ip) ON DUPLICATE KEY UPDATE payload=:val",[':id'=>$key.$ip,':key'=>$key,':val'=>$val,':ip'=>$ip]);		
	}

	public static function get($key,$database = false){
		if(!$database) return self::getcookie($key);
		$vals = DataBase::select("SELECT payload FROM sessions WHERE id=:id",[':id'=>$key.GetClientIP()]);	
		if(count($vals)> 0) return $vals[0]['payload'];
		else return null;
	}

	public static function pull($key,$database = false){
		$val = self::get($key,$database);
		forget($key,$database);
		return $val;
	}

	public static function has($key,$database = false){
		if(!$database) return self::getcookie($key)!= null;
		$vals = DataBase::select("SELECT payload FROM sessions WHERE id=:id",[':id'=>$key.GetClientIP()]);	
		return count($vals)> 0;
	}

	public static function flush($database = false){
		if(!$database) return self::flushcookies();
		return DataBase::delete("DELETE FROM sessions WHERE ip_address=:ip",[':ip'=>GetClientIP()]);		
	}
	
	public static function forget($key,$database = false){
		if(!$database) return self::forgetcookie($key);
		return DataBase::delete("DELETE FROM sessions WHERE id=:id",[':id'=>$key.GetClientIP()]);		
	}

	public static function putcookie($key,$val){
		return setcookie($key, $val, time() + self::$Time,"/");
	}

	public static function getcookie($key){
		if(isset($_COOKIE[$key])) return $_COOKIE[$key];
		else return null;
	}

	public static function forgetcookie($key){
		unset($_COOKIE[$key]);
		return setcookie($key, "", time() - self::$Time,"/");
	}

	public static function flushcookies(){
		foreach($_COOKIE as $key => $val)
			if(strpos($key,self::$Prefix) === 0) self::forgetcookie($key);
	}
}
?>