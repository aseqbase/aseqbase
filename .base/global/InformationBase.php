<?php
/**
 *All the basic website informations
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Structures See the Structures Documentation
 */
abstract class InformationBase extends Base{
	/**
	 * The website owner name
	 * @var mixed
	 */
	public $Owner = null;
	/**
     * The website owner full name
     * @var mixed
	 */
	public $FullOwner = null;
	/**
     * Descriptions about the website owner
     * @field strings
     * @var mixed
	 */
	public $OwnerDescription = null;
	/**
     * The website name
	 * @var mixed
	 */
	public $Product = null;
	/**
     * The website full name
	 * @var mixed
	 */
	public $FullProduct = null;
	/**
     * The website owner and name
	 * @var mixed
	 */
	public $Name = null;
	/**
     * The website full owner and full name
	 * @var mixed
	 */
	public $FullName = null;
	/**
     * The short slogan of the website
	 * @var mixed
	 */
	public $Slogan = null;
	/**
     * The more detailed slogan of the website
     * @field strings
     * @var mixed
	 */
	public $FullSlogan = null;
	/**
     * The short description of the website
     * @var mixed
	 */
	public $Description = null;
	/**
     * The more detailed description of the website
     * @field strings
     * @var mixed
	 */
	public $FullDescription = null;

	/**
	 * The main path
     * @field path
	 * @var mixed
	 */
	public $Path = null;
	/**
     * The website main logo path
     * @field path
     * @var mixed
	 */
	public $LogoPath = "/file/logo/logo.png";
	/**
     * The website full logo path
     * @field path
	 * @var mixed
	 */
	public $FullLogoPath = "/file/logo/full-logo.png";
	/**
     * The website main banner path
     * @field path
	 * @var mixed
	 */
	public $BannerPath = null;
	/**
     * The website full logo path
     * @field path
	 * @var mixed
	 */
	public $FullBannerPath = null;
	/**
     * The default homepage path
     * @field path
	 * @var mixed
	 */
	public $HomePath = "/home";
	/**
     * The default download links path
     * @field path
	 * @var mixed
	 */
	public $DownloadPath = null;
	/**
     * The default payment path
     * @field path
	 * @var mixed
	 */
	public $PaymentPath = null;
	/**
     * The default payment contents to show for paying process
     * @field strings
	 * @var mixed
	 */
	public $PaymentContent = null;
	/**
     * The main symbol path to show while system is waiting
     * @field path
	 * @var mixed
	 */
	public $WaitSymbolPath = "/file/general/wait.gif";
	/**
     * The main symbol path to show while system is processing
     * @field path
	 * @var mixed
	 */
	public $ProcessSymbolPath = "/file/general/process.gif";
	/**
     * The main symbol path to show on the errors
     * @field path
	 * @var mixed
	 */
	public $ErrorSymbolPath = "/file/general/error.png";

	/**
	 * The user service
     * @internal
	 * @var \MiMFa\Library\User
	 */
	public $User = null;
	/**
     * The location link on the map
     * @field map
	 * @var mixed
	 */
	public $Location = null;

	/**
	 * The main KeyWords of the website, these will effect on SEO and views
     * @field array
     * @var array
	 */
	public $KeyWords = array("MiMFa","Minimal Member Factory");

	/**
     * The main menu to show on the most pages
     * @field array
     * @var array
	 */
	public $MainMenus = array(
		array("Name"=>"HOME","Link"=>"/home","Image"=>"/file/symbol/home.png","Attributes"=> "class='menu-link'")
	);

	/**
     * The side menu to show on the most pages
     * @field array
     * @var array
	 */
	public $SideMenus = null;

	/**
     * The main shortcut menu to show on the most pages
     * @field array
     * @var array
	 */
	public $Shortcuts = null;

	/**
     * The main menu items to show on the first page
     * @field array
     * @var array
	 */
	public $Services = null;

	/**
     * The main members and personals of the website
     * @field array
     * @var array
	 */
	public $Members = array();

	/**
     * The main contacts details to show on the most pages and contact page
     * @field array
     * @var array
	 */
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