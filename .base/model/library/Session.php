<?php namespace MiMFa\Library;
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

	public function __construct(DataTable $dataTable, Cryptograph $cryptograph){
		$this->DataTable = $dataTable;
		$this->Cryptograph = $cryptograph;
        if(!$this->isAlive()) $this->Start();
    }
    /**
    * Stores datas in the session.
    * Example: $instance->foo = 'bar';
    *
    * @param    $name    Name of the datas.
    * @param    $value    Your datas.
    * @return    void
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
    * Destroys the current session.
    *
    * @return    bool    TRUE is session has been deleted, else FALSE.
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

	public function Start(){
        $this->StartSecure();
		if(is_null($this->GetId())){
			$this->SetId(\_::$Aseq->Name);
            $this->Set("Ip" , getClientIp());
			return true;
        }
        return false;
	}
	public function Restart(){
		$this->Stop();
		return $this->Start();
	}
	public function Stop(){
		$this->FlushData();
		$this->FlushCookie();
		$this->FlushSecure();
		$this->FlushId();
	}

	public function SetId($id){
		return $_SESSION["SESSION_ID"] = $id;
    }
	public function GetId(){
		return takeValid($_SESSION, "SESSION_ID", null);
    }
	public function PopId(){
		$val = $this->GetId();
		$this->ForgetId();
		return $val;
	}
	public function HasId(){
		return isset($_SESSION["SESSION_ID"]);
    }
	public function ForgetId(){
		unset($_SESSION["SESSION_ID"]);
    }
	public function FlushId(){
		unset($_SESSION["SESSION_ID"]);
	}

	public function Set($key,$val){
		if(\_::$Config->ClientSession) return $this->SetCookie($key,$val);
		else return $this->SetData($key,$val);
    }
	public function Get($key){
		if(\_::$Config->ClientSession) return $this->GetCookie($key);
		return $this->GetData($key);
	}
	public function Pop($key){
		$val = $this->Get($key);
		$this->Forget($key);
		return $val;
	}
	public function Has($key){
		if(\_::$Config->ClientSession) return $this->GetCookie($key)!= null;
		return $this->HasData($key);
	}
	public function Forget($key){
		if(\_::$Config->ClientSession) return $this->ForgetCookie($key);
		return $this->ForgetData($key);
	}
	public function Flush(){
		if(\_::$Config->ClientSession) return $this->FlushCookie();
		return $this->FlushData();
	}

	public function SetData($key,$val){
		return $this->DataTable->Replace([':Key'=>$this->ToCipherKey($key), ':Value'=>$this->ToCipherValue($val), ':Ip'=>getClientIp()]);
    }
	public function GetData($key){
		return $this->ToPlainValue($this->DataTable->SelectValue("Value" , "`Key`=:Key", [':Key'=>$this->ToCipherKey($key)]));
	}
	public function PopData($key){
		$val = $this->GetData($key);
		$this->ForgetData($key);
		return $val;
	}
	public function HasData($key){
		return $this->DataTable->Exists("`Key`=:Key",[':Key'=>$this->ToCipherKey($key)]);
	}
	public function ForgetData($key){
		return $this->DataTable->Delete("`Key`=:Key",[':Key'=>$this->ToCipherKey($key)]);
	}
	public function FlushData(){
		return $this->DataTable->Delete("`Key` LIKE '".$this->GetId().$this->Separator."%'");
	}

	public function SetCookie($key,$val){
		if($val == null) return false;
		return setcookie($this->ToCipherKey($key), $this->ToCipherValue($val), time() + $this->Time, "/");
	}
	public function GetCookie($key){
		$key = $this->ToCipherKey($key);
		if(isset($_COOKIE[$key])) return $this->ToPlainValue($_COOKIE[$key]);
		else return null;
	}
	public function PopCookie($key){
		$val = $this->GetCookie($key);
		$this->ForgetCookie($key);
		return $val;
	}
	public function HasCookie($key){
		return !is_null($this->GetCookie($key));
	}
	public function ForgetCookie($key){
		$key = $this->ToCipherKey($key);
		unset($_COOKIE[$key]);
		return setcookie($key, "", time() - $this->Time,"/");
	}
	public function FlushCookie(){
		$sk = $this->GetId().$this->Separator;
		foreach($_COOKIE as $key => $val)
			if(startsWith($key,$sk)){
                unset($_COOKIE[$key]);
                setcookie($key, "", time() - $this->Time,"/");
            }
	}


	public function StartSecure(){
        if(session_id() == "")
			session_start([
				'cookie_lifetime' => $this->Time
			]);
        return session_id() != "";
    }
	public function SetSecure($key,$val){
		return $_SESSION[$this->ToKey($key)] = $this->Encrypt($val);
    }
	public function GetSecure($key){
		$key = $this->ToKey($key);
		if(isset($_SESSION[$key])) return $this->Decrypt($_SESSION[$key]);
		else return null;
    }
	public function PopSecure($key){
		$key = $this->ToKey($key);
		$val = $this->GetSecure($key);
		$this->ForgetSecure($key);
		return $val;
	}
	public function HasSecure($key){
		return isset($_SESSION[$this->ToKey($key)]);
	}
	public function ForgetSecure($key){
		unset($_SESSION[$this->ToKey($key)]);
    }
	public function FlushSecure(){
		$id = $this->GetId().$this->Separator;
		foreach($_SESSION as $key => $val)
			if(startsWith($key, $id))
				unset($_SESSION[$key]);
	}


	protected function ToKey($key){
		return substr($this->GetId().$this->Separator.$key,0,65);
    }
	protected function ToCipherKey($key){
		if(\_::$Config->EncryptSessionKey) return $this->ToKey($this->Encrypt($key));
		else return $this->ToKey($key);
    }
	protected function ToPlainKey($key){
		$ks = explode($this->Separator,$key);
		if(\_::$Config->EncryptSessionKey) return $this->Decrypt(end($ks));
		return end($ks);
    }
	protected function ToCipherValue($value){
		if(\_::$Config->EncryptSessionValue) return $this->Encrypt($value);
		else return $value;
    }
	protected function ToPlainValue($value){
		if(\_::$Config->EncryptSessionValue) return $this->Decrypt($value);
		else return $value;
    }

	public function Encrypt($plain){
		if(is_null($plain)) return null;
		if(empty($plain)) return $plain;
		return $this->Cryptograph->Encrypt($plain,\_::$Config->SecretKey, true);
    }
	public function Decrypt($cipher){
		if(is_null($cipher)) return null;
		if(empty($cipher)) return $cipher;
		try{
            return $this->Cryptograph->Decrypt($cipher,\_::$Config->SecretKey, true);
        }
        catch (\Exception $exception)
        {
			return null;
        }
    }
}