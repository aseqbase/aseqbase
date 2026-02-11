<?php
class AseqFront extends FrontBase
{
	/**
	 * The website owner name
	 * @var mixed
	 * @category Information
	 */
	public $Owner = "MiMFa";/**
	 * The website owner full name
	 * @var mixed
	 * @category Information
	 */
	public $FullOwner = "Minimal Members Factory";
	/**
	 * The website owner and name
	 * @category Information
	 * @var mixed
	 */
	public $Name = "aseqbase";
	/**
	 * The website full owner and full name
	 * @category Information
	 * @var mixed
	 */
	public $FullName = "MiMFa aseqbase";
	/**
	 * The short slogan of the website
	 * @field string
	 * @category Information
	 * @var mixed
	 */
	public $Slogan = "<u>a seq</u>uence-<u>base</u>d framework";
	/**
	 * The more detailed slogan of the website
	 * @field texts
	 * @category Information
	 * @var mixed
	 */
	public $FullSlogan = "Develop websites by <u>a seq</u>uence-<u>base</u>d framework";
	/**
	 * The short description of the website
	 * @field texts
	 * @category Information
	 * @var mixed
	 */
	public $Description = "An original, safe, very flexible, and innovative framework for web developments!";
	/**
	 * The more detailed description of the website
	 * @field content
	 * @category Information
	 * @var mixed
	 */
	public $FullDescription = "A special framework for web development called \"aseqbase\" (a sequence-based framework) has been developed to implement safe, flexible, fast, and strong pure websites based on that, since 2018 so far.";
	/**
     * The copyright of the website
     * @field texts
	 * @category Information
     * @var mixed
	 */
	public $CopyRight = null;

	/**
     * The website main banner path
     * @field image
	 * @category Front
	 * @var mixed
	 */
	public $BannerPath = null;
	/**
     * The website full logo path
     * @field image
	 * @category Front
	 * @var mixed
	 */
	public $FullBannerPath = null;
	/**
     * The default homepage path
     * @field path
	 * @category Information
	 * @var mixed
	 */
	public $HomePath = "/home";
	/**
     * The default download links path
     * @field path
	 * @category Information
	 * @var mixed
	 */
	public $DownloadPath = null;
	/**
     * The main symbol path to show while system is waiting
     * @field image
	 * @category Front
	 * @var mixed
	 */
	public $WaitSymbolPath = "/asset/general/wait.gif";
	/**
     * The main symbol path to show while system is processing
     * @field image
	 * @category Front
	 * @var mixed
	 */
	public $ProcessSymbolPath = "/asset/general/process.gif";
	/**
     * The main symbol path to show on the errors
     * @field image
	 * @category Front
	 * @var mixed
	 */
	public $ErrorSymbolPath = "/asset/general/error.gif";

	/**
     * The location link on the map
     * @field map
	 * @category Information
	 * @var mixed
	 */
	public $Location = null;

	/**
	 * The main KeyWords of the website, these will effect on SEO and views
	 * @field array
	 * @category Optimize
	 * @var array
	 */
	public $KeyWords = array("MiMFa aseqbase Framework", "MiMFa", "aseqbase", "Web Development", "Development", "Web Framework", "Website", "Framework");

	/**
     * The main menu to show on the most pages
     * @field array<array>
	 * @category Render
     * @var array|null
	 */
	public $MainMenus = [
		array("Name" => "HOME", "Path"=> "/home", "Image" => "home")
	];

	/**
     * The side menu to show on the most pages
     * @field array<array>
	 * @category Render
     * @var array|null
	 */
	public $SideMenus = null;

	/**
     * The main shortcut menu to show on the most pages
     * @field array<array>
	 * @category Render
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
     * @field array<array>
	 * @category Render
     * @var array|null
	 */
	public $Services = null;

	/**
     * The main members and personnel of the website
     * @field array<array>
	 * @category Render
     * @var array|null
	 */
	public $Members = [];

	/**
     * The main contacts details to show on the most pages and contact page
     * @field array<array>
	 * @category Render
     * @var array|null
	 */
	public $Contacts = [];

	/**
	 * The website default template class
	 * @var string
	 * @default "Main"
	 * @field value
	 * @category Template
	 */
	public $DefaultTemplate = "Main";
	/**
	 * @category Template
	 */
	public $DetectMode = true;
	/**
	 * @category Template
	 */
	public $AnimationSpeed = 500;

	
	/**
	 * Allow to translate all text by internal algorithms
	 * @var bool
	 * @category Language
	 */
	public $AllowTranslate = false;
	/**
	 * Allow to detect the client language automatically
	 * @var bool
	 * @category Language
	 */
	public $AutoDetectLanguage = true;
	/**
	 * Allow to update the language by translator automatically
	 * @var bool
	 * @category Language
	 */
	public $AutoUpdateLanguage = false;

	/**
	 * The Date Time Format
	 * @var string
	 * @example: "Y-m-d H:i:s" To show like 2018-08-10 14:46:45
	 * @field value
	 * @category Time
	 */
	public $DateTimeFormat = 'H:i, d M y';

	public $AllowReduceSize = true;
	public $AllowTextAnalyzing = true;
	public $AllowContentReferring = true;
	public $AllowCategoryReferring = false;
	public $AllowTagReferring = true;
	public $AllowUserReferring = false;
	public $AllowSelecting = true;
	public $AllowContextMenu = true;

	/**
	 * Allow to leave comments on posts
	 * @var bool
	 * @category Content
	 */
	public $AllowWriteComment = true;
	/**
	 * Access level to leave comments on posts
	 * @var int
	 * @category Content
	 */
	public $WriteCommentAccess = 1;
	/**
	 * Allow to read comments on posts
	 * @var bool
	 * @category Content
	 */
	public $AllowReadComment = true;
	/**
	 * Access level to read comments on posts
	 * @var int
	 * @category Content
	 */
	public $ReadCommentAccess = 0;
	/**
	 * Default status of new comments on posts
	 * @var int
	 * @category Content
	 */
	public $DefaultCommentStatus = 0;

	/**
	 * Default site key for ReCaptcha
	 * @var string
	 * @category Security
	 */
	public $ReCaptchaSiteKey = null;
	
	
	public function __construct(){
		parent::__construct();
		
		$menu = between($this->MainMenus,$this->SideMenus,$this->Shortcuts,$this->Services);
		if(is_null($this->MainMenus)) $this->MainMenus = $menu;
		if(is_null($this->SideMenus)) $this->SideMenus = $menu;
		if(is_null($this->Shortcuts)) $this->Shortcuts = $menu;
		if(is_null($this->Services)) $this->Services = $menu;
	
		$this->LogoPath = asset("logo/logo-".$this->Translate->Language.".svg")??$this->LogoPath;
		$this->BrandLogoPath = asset("logo/brand-logo-".$this->Translate->Language.".svg")??$this->BrandLogoPath;
		$this->FullLogoPath = asset("logo/full-logo-".$this->Translate->Language.".svg")??$this->FullLogoPath;
	}
}