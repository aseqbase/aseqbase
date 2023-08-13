<?php
/**
 *All the basic website informations
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Structures See the Structures Documentation
 */
abstract class InformationBase extends Base{
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
	public $PaymentPath = null;
	public $PaymentContent = null;
	public $WaitSymbolPath = "/file/general/wait.gif";
	public $ProcessSymbolPath = "/file/general/process.gif";
	public $ErrorSymbolPath = "/file/general/error.png";
	
	/**
	 * The user service
	 * @var \MiMFa\Library\User
	 */
	public $User = null;

	public $Location = null;

	public $KeyWords = array("MiMFa","Minimal Member Factory");

	public $MainMenus = array(
		array("Name"=>"HOME","Link"=>"/home","Image"=>"/file/symbol/home.png","Attributes"=> "class='menu-link'")
	);


	public $SideMenus = null;

	public $Shortcuts = null;

	public $Services = null;

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

		$menu = between($this->MainMenus,$this->SideMenus,$this->Shortcuts,$this->Services);
		if(!isValid($this->MainMenus)) $this->MainMenus = $menu;
		if(!isValid($this->SideMenus)) $this->SideMenus = $menu;
		if(!isValid($this->Shortcuts)) $this->Shortcuts = $menu;
		if(!isValid($this->Services)) $this->Services = $menu;

		$this->MainMenus = forceUrls($this->MainMenus);
		$this->SideMenus = forceUrls($this->SideMenus);
		$this->Shortcuts = forceUrls($this->Shortcuts);
		$this->Services = forceUrls($this->Services);
		$this->Members = forceUrls($this->Members);
		$this->Contacts = forceUrls($this->Contacts);
	}

}
?>