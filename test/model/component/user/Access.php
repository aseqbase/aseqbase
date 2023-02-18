<?php namespace MiMFa\Component;
class Access extends \MiMFa\Base{
	public $Id = null;
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

	public function SignUp(){

	}
	public function SignIn(){

	}
	public function SignOut(){

	}
}
?>