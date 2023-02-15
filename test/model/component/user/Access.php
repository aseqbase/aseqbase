<?php namespace MiMFa\Component;
class Access{
	public $Group = null;

	public $UserName = null;
	public $Password = null;
	public $Hint = null;
	
	public $Session = null;

	public function __construct($id = null){
		$this->Id = $id;
		$this->Update($id);
	}

	public function Update($id){

	}

	public function Modify(){

	}
}
?>