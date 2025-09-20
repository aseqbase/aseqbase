<?php
class InformationAseq extends InformationBase
{
	public $Owner = "MiMFa";
	public $FullOwner = "Minimal Members Factory";
	public $Name = "aseqbase";
	public $FullName = "MiMFa aseqbase";
	public $Slogan = "<u>a seq</u>uence-<u>base</u>d framework";
	public $FullSlogan = "Develop websites by <u>a seq</u>uence-<u>base</u>d framework";
	public $Description = "An original, safe, very flexible, and innovative framework for web developments!";
	public $FullDescription = "A special framework for web development called \"aseqbase\" (a sequence-based framework) has been developed to implement safe, flexible, fast, and strong pure websites based on that, since 2018 so far.";
	/**
     * The copyright of the website
     * @field strings
     * @var mixed
	 */
	public $CopyRight = null;

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
     * The main symbol path to show while system is waiting
     * @field path
	 * @var mixed
	 */
	public $WaitSymbolPath = "/asset/general/wait.gif";
	/**
     * The main symbol path to show while system is processing
     * @field path
	 * @var mixed
	 */
	public $ProcessSymbolPath = "/asset/general/process.gif";
	/**
     * The main symbol path to show on the errors
     * @field path
	 * @var mixed
	 */
	public $ErrorSymbolPath = "/asset/general/error.gif";

	/**
     * The location link on the map
     * @field map
	 * @var mixed
	 */
	public $Location = null;

	public $KeyWords = array("MiMFa aseqbase Framework", "MiMFa", "aseqbase", "Web Development", "Development", "Web Framework", "Website", "Framework");

	/**
     * The main menu to show on the most pages
     * @field array
     * @var array|null
	 */
	public $MainMenus = [
		array("Name" => "HOME", "Path"=> "/home", "Image" => "home")
	];

	/**
     * The side menu to show on the most pages
     * @field array
     * @var array|null
	 */
	public $SideMenus = null;

	/**
     * The main shortcut menu to show on the most pages
     * @field array
     * @var array|null
	 */
	public $Shortcuts = [
		array("Name" => "Menu", "Path"=> "viewSideMenu()", "Image" => "bars"),
		array("Name" => "Posts", "Path"=> "/posts", "Image" => "th-large"),
		array("Name" => "Home", "Path"=> "/home", "Image" => "home"),
		array("Name" => "Contact", "Path"=> "/contact", "Image" => "phone"),
		array("Name" => "About", "Path"=> "/about", "Image" => "quote-left")
	];

	/**
     * The main menu items to show on the first page
     * @field array
     * @var array|null
	 */
	public $Services = null;

	/**
     * The main members and personnel of the website
     * @field array
     * @var array|null
	 */
	public $Members = [];

	/**
     * The main contacts details to show on the most pages and contact page
     * @field array
     * @var array|null
	 */
	public $Contacts = [];

	public function __construct(){
		parent::__construct();
		
		$menu = between($this->MainMenus,$this->SideMenus,$this->Shortcuts,$this->Services);
		if(is_null($this->MainMenus)) $this->MainMenus = $menu;
		if(is_null($this->SideMenus)) $this->SideMenus = $menu;
		if(is_null($this->Shortcuts)) $this->Shortcuts = $menu;
		if(is_null($this->Services)) $this->Services = $menu;
	
		$this->LogoPath = asset("logo/logo-".\_::$Back->Translate->Language.".svg")??$this->LogoPath;
		$this->BrandLogoPath = asset("logo/brand-logo-".\_::$Back->Translate->Language.".svg")??$this->BrandLogoPath;
		$this->FullLogoPath = asset("logo/full-logo-".\_::$Back->Translate->Language.".svg")??$this->FullLogoPath;
	}
}