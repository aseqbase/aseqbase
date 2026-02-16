<?php

use MiMFa\Library\Storage;
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
	 * Fonts Palette
	 * @field array<font>
	 * @template array [normal, inside, outside]
	 * @category Template
	 * @var mixed
	 */
	public $FontPalette = array("'Dubai-Light', sans-serif", "'Dubai', sans-serif", "'Dubai', sans-serif");
	/**
	 * Shadows Palette
	 * @field array<{'size' 'size' 'size' 'color'}>
	 * @field array<text>
	 * @template array [minimum, normal, medium, maximum, ...]
	 * @category Template
	 * @var mixed
	 */
	public $ShadowPalette = array("none", "4px 7px 20px #00000005", "4px 7px 20px #00000015", "4px 7px 20px #00000030", "5px 10px 25px #00000030", "5px 10px 25px #00000050", "5px 10px 50px #00000050");
	/**
	 * Borders Palette
	 * @field array<{'size', ['solid','double','dotted','dashed']}>
	 * @field array<text>
	 * @template array [minimum, normal, medium, maximum, ...]
	 * @category Template
	 * @var mixed
	 */
	public $BorderPalette = array("0px", "1px solid", "2px solid", "5px solid", "10px solid", "25px solid");
	/**
	 * Radiuses Palette
	 * @field array<size>
	 * @template array [minimum, normal, medium, maximum, ...]
	 * @category Template
	 * @var mixed
	 */
	public $RadiusPalette = array("unset", "3px", "5px", "25px", "50%", "100%");
	/**
	 * Transitions Palette
	 * @field array<text>
	 * @template array [minimum, normal, medium, maximum, ...]
	 * @category Template
	 * @var mixed
	 */
	public $TransitionPalette = array("none", "all .25s linear", "all .5s linear", "all .75s linear", "all 1s linear", "all 1.5s linear");
	/**
	 * Overlays Palette
	 * @field array<path>
	 * @template array [minimum, normal, medium, maximum, ...]
	 * @category Template
	 * @var mixed
	 */
	public $OverlayPalette = array("/asset/overlay/glass.png", "/asset/overlay/cotton.png", "/asset/overlay/cloud.png", "/asset/overlay/wings.svg", "/asset/overlay/sands.png", "/asset/overlay/dirty.png");
	/**
	 * Patterns Palette
	 * @field array<path>
	 * @template array [minimum, normal, medium, maximum, ...]
	 * @category Template
	 * @var mixed
	 */
	public $PatternPalette = array("/asset/pattern/main.svg", "/asset/pattern/doddle.png", "/asset/pattern/doddle-fantasy.png", "/asset/pattern/triangle.png", "/asset/pattern/slicksline.png", "/asset/pattern/doddle-mess.png");


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

	/**
	 * @category Template
	 * @var int
	 */
	public $AnimationSpeed = 0;
	/**
	 * @category Template
	 */
	public $DetectMode = true;
	/**
	 * @category Template
	 */
	public $SwitchMode = null;
	/**
	 * @category Template
	 * @field short
	 */
	public $DefaultMode = null;
	/**
	 * @category Template
	 * @field short
	 */

	public $CurrentMode = null;

	/**
	 * @field value
	 * @category Template
	 * @var string
	 */
	public $SwitchRequest = "SwitchMode";

	public $AllowReduceSize = true;
	public $AllowTextAnalyzing = true;
	public $AllowContentReferring = true;
	public $AllowCategoryReferring = false;
	public $AllowTagReferring = true;
	public $AllowUserReferring = false;
	
	
	public function __construct(){
		parent::__construct();
		
		$this->DefaultMode = $this->CurrentMode = $this->GetMode($this->BackColor(0));
		$this->SwitchMode = received($this->SwitchRequest) ?? getMemo($this->SwitchRequest) ?? $this->SwitchMode;
		
		if ($this->SwitchMode) {
			$middle = $this->ForeColorPalette;
			$this->ForeColorPalette = $this->BackColorPalette;
			$this->BackColorPalette = $middle;
			$this->CurrentMode = $this->GetMode($this->BackColor(0));
		}

		$menu = between($this->MainMenus,$this->SideMenus,$this->Shortcuts,$this->Services);
		if(is_null($this->MainMenus)) $this->MainMenus = $menu;
		if(is_null($this->SideMenus)) $this->SideMenus = $menu;
		if(is_null($this->Shortcuts)) $this->Shortcuts = $menu;
		if(is_null($this->Services)) $this->Services = $menu;
	
		$this->LogoPath = asset("logo/logo-".$this->Translate->Language.".svg")??$this->LogoPath;
		$this->BrandLogoPath = asset("logo/brand-logo-".$this->Translate->Language.".svg")??$this->BrandLogoPath;
		$this->FullLogoPath = asset("logo/full-logo-".$this->Translate->Language.".svg")??$this->FullLogoPath;
	}

	/**
	 * Get the lightness of a color with a number between -255 to +255
	 * @param mixed $color A three, four, six or eight characters hexadecimal color numbers for example #f80 or #ff8800
	 * @return float|int A number between -255 (for maximum in darkness) to +255 (for maximum in lightness)
	 */
	public function GetMode($color = null)
	{
		if (!isValid($color))
			if (!is_null($this->CurrentMode))
				return $this->CurrentMode;
			else
				return $this->GetMode($this->BackColor(0));
		$l = strlen($color) > 6;
		$rgb = preg_find_all($l ? '/\w\w/' : '/\w/', $color);
		$sc = ($l ? hexdec(getValid($rgb, 0, 0)) + hexdec(getValid($rgb, 1, 0)) + hexdec(getValid($rgb, 2, 0)) :
			hexdec(getValid($rgb, 0, 0)) * 16 + hexdec(getValid($rgb, 1, 0)) * 16 + hexdec(getValid($rgb, 2, 0)) * 16 - 3);
		return $sc > 510 ? $sc - 510 : ($sc < 255 ? $sc - 255 : $sc - 382.5);
	}
	
	/**
	 * To get the Font by index
	 * @param int $index 0:normal, 1:inside, 2:outside
	 */
	public function Font(int $index = 0)
	{
		return $this->LoopPalette($this->FontPalette, $index);
	}
	/**
	 * To get the Shadow by index
	 * @param int $index 0:minimum, 1:normal, 2:medium, 3:maximum,...
	 */
	public function Shadow(int $index = 0)
	{
		return $this->LimitPalette($this->ShadowPalette, $index);
	}
	/**
	 * To get the Border size by index
	 * @param int $index 0:minimum, 1:normal, 2:medium, 3:maximum,...
	 */
	public function Border(int $index = 0)
	{
		return $this->LimitPalette($this->BorderPalette, $index);
	}
	/**
	 * To get the Radius size by index
	 * @param int $index 0:minimum, 1:normal, 2:medium, 3:maximum,...
	 */
	public function Radius(int $index = 0)
	{
		return $this->LimitPalette($this->RadiusPalette, $index);
	}
	/**
	 * To get the Transition by index
	 * @param int $index 0:minimum, 1:normal, 2:medium, 3:maximum,...
	 */
	public function Transition(int $index = 0)
	{
		return $this->LimitPalette($this->TransitionPalette, $index);
	}
	/**
	 * To get the Overlay image by index
	 * @param int $index 0:minimum, 1:normal, 2:medium, 3:maximum,...
	 */
	public function Overlay(int $index = 0)
	{
		return Storage::GetUrl($this->LoopPalette($this->OverlayPalette, $index));
	}
	/**
	 * To get the Pattern image by index
	 * @param int $index 0:minimum, 1:normal, 2:medium, 3:maximum,...
	 */
	public function Pattern(int $index = 0)
	{
		return Storage::GetUrl($this->LoopPalette($this->PatternPalette, $index));
	}

}