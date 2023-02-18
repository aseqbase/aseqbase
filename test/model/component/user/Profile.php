<?php namespace MiMFa\Component;
      class Profile extends \MiMFa\Base{
	public $Id = null;
	public $Image = null;
	public $Name = null;
	public $FirstName = null;
	public $MiddleName = null;
	public $LastName = null;
	public $BirthDate = null;
	public $IdNumber = null;
	public $Email = null;
	public $Website = null;
	public $SocialNetwork = null;
	public $Mobile = null;
	public $Phone = null;
	public $Country = null;
	public $Province = null;
	public $City = null;
	public $State = null;
	public $Address = null;
	public $Location = null;
	public $Job = null;
	public $JobAddress = null;
	public $Description = null;

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