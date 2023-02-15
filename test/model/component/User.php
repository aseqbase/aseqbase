<?php namespace MiMFa\Component;
class User extends Component{
	private $Id = null;
	
	public $Profile = null;
	public $Access = null;

	public function __construct(){
		parent::__construct();
		$this->Id = $_SESSION["User"];
	}

	public function Update(){
		$this->Profile = new Profile($this->Id);
		$this->Access = new Access($this->Id);
	}

	public function Modify(){
		$this->Profile = new Profile($this->Id);
		$this->Access->Modify();
	}

	public function SignUp(){
		$this->Access = new Access();
		$this->Access->SignUp();
	}

	public function SignIn(){
		$this->Access = new Access();
		$this->Access->SignIn();
	}

	public function SignOut(){
		$this->Access = new Access($this->Id);
		$this->Access->SignOut();
	}
}
?>