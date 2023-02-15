<?php namespace MiMFa\Model;
class User{
	public $Name = null;
	public $FirstName = null;
	public $LastName = null;
	public $Image = "/file/logo/logo.gif";
	public $Accesses = array();

	public $IsGuest = true;

	public function __construct(){
		$this->SetImage(\_::$FILE_DIR."logo/","logo");
	}

    public function SetImage($dir,$fileName="logo"){
		$path = $dir.$fileName;
		if(file_exists("$path.svg")) $this->Image = "/file/logo/$fileName.svg";
		elseif(file_exists("$path.gif")) $this->Image = "/file/logo/$fileName.gif";
		elseif(file_exists("$path.png")) $this->Image = "/file/logo/$fileName.png";
		elseif(file_exists("$path.ico")) $this->Image = "/file/logo/$fileName.ico";		
		elseif(file_exists("$path.jpg")) $this->Image = "/file/logo/$fileName.jpg";
		else $this->Image = \MiMFa\Library\Local::GetUrl("/file/logo/$fileName.png");
	}

	public function Access($task){
		return in_array($task,$this->Accesses);
	}
}
?>