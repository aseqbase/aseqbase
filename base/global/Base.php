<?php
class Base{
	public $Name = null;
	
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

	public function Set_Defaults(){
		$this->Name = \_::$CONFIG->AllowEncryptNames?(substr($this->Get_Namespace(),0,1).RandomString(10)):($this->Name??$this->get_className())."_".$this->Get_Namespace();
	}
}
?>