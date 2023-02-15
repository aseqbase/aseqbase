<?php
abstract class InformationBase extends \MiMFa\Base{
	public $Owner = null;
	public $FullOwner = null;
	public $OwnerDescription = null;
	public $Product = null;
	public $FullProduct = null;
	public $Name = null;
	public $FullName = null;
	public $Slogan = null;
	public $FullSlogan = null;
	public $Description = null;
	public $FullDescription = null;
	
	public $Path = null;
	public $LogoPath = "/file/logo/logo.png";
	public $FullLogoPath = "/file/logo/full-logo.png";
	public $BannerPath = null;
	public $FullBannerPath = null;
	public $HomePath = "/home";
	public $DownloadPath = null;
	public $WaitSymbolPath = "/file/general/wait.gif";
	public $ProcessSymbolPath = "/file/general/process.gif";
	public $ErrorSymbolPath = "/file/general/error.png";
	
	public $User = null;
	
	public $Location = null;
	
	public $KeyWords = array("MiMFa","Minimal Member Factory");

	public $MainMenus = array(
		array("Layer"=>1,"Name"=>"HOME","Link"=>"/home","Image"=>"/file/symbol/home.png","Attributes"=> "class='menu-link'")
	);


	public $SideMenus = array(
		array("Layer"=>1,"Name"=>"HOME","Link"=>"/home","Image"=>"/file/symbol/home.png","Attributes"=> "class='menu-link'")
	);

	public $Shortcuts = array(
		array("Name"=>"Home","Link"=>"#internal","Image"=>"/file/symbol/home.png","Attributes"=> "class='internal-link' data-target='.page' onclick='ViewInternal(\"/home\",\"fade\"); ViewSideMenu(false);'")
	);
		
	public $Services = array();

	public $Members = array();
	
	public $Contacts = array();

	public function __construct(){
		$this->LogoPath = forceUrl($this->LogoPath);
		$this->FullLogoPath = forceUrl($this->FullLogoPath);
		$this->BannerPath = forceUrl($this->BannerPath);
		$this->FullBannerPath = forceUrl($this->FullBannerPath);
		$this->WaitSymbolPath = forceUrl($this->WaitSymbolPath);
		$this->ProcessSymbolPath = forceUrl($this->ProcessSymbolPath);
		$this->ErrorSymbolPath = forceUrl($this->ErrorSymbolPath);
		$this->MainMenus = forceUrls($this->MainMenus);
		$this->SideMenus = forceUrls($this->SideMenus);
		$this->Shortcuts = forceUrls($this->Shortcuts);
		$this->Services = forceUrls($this->Services);
		$this->Members = forceUrls($this->Members);
		$this->Contacts = forceUrls($this->Contacts);
	}
}
?>