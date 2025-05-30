<?php
library("Revise");
/**
 *All the basic website informations
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Structures See the Structures Documentation
 */
abstract class InformationBase extends ArrayObject{
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
     * The copyright of the website
     * @field strings
     * @var mixed
	 */
	public $CopyRight = null;

	/**
	 * Default mail sender
	* @example: "do-not-reply@mimfa.net"
	* @field array
	* @var string|null|array<string>
	* @category Security
	*/
	public $SenderEmail = null;
	/**
	 * Default mail reciever
	 * @example: "info@mimfa.net"
	 * @field array
	 * @var string|null|array<string>
	 * @category Security
	 */
	public $ReceiverEmail = null;
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
	public $LogoPath = "/asset/logo/logo.png";
	/**
     * The website full logo path
     * @field path
	 * @var mixed
	 */
	public $FullLogoPath = "/asset/logo/full-logo.png";
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
     * The default payment JSON data to use for paying process
     * @field json
     * @example '{"Network":"TRC-20","Unit":"USDT","DestinationContent":"TLQrvG1sNKY2kNRfcBUgW4QLfe1zAtZQds"}'
     * @var mixed
     */
	public $Payment = null;
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
	public $ErrorSymbolPath = "/asset/general/error.png";

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
	public $KeyWords = [];

	/**
     * The main menu to show on the most pages
     * @field array
     * @var array
	 */
	public $MainMenus = [];

	/**
     * The side menu to show on the most pages
     * @field array
     * @var array
	 */
	public $SideMenus = [];

	/**
     * The main shortcut menu to show on the most pages
     * @field array
     * @var array
	 */
	public $Shortcuts = [];

	/**
     * The main menu items to show on the first page
     * @field array
     * @var array
	 */
	public $Services = [];

	/**
     * The main members and personnel of the website
     * @field array
     * @var array
	 */
	public $Members = [];

	/**
     * The main contacts details to show on the most pages and contact page
     * @field array
     * @var array
	 */
	public $Contacts = [];


	public function __construct(){
		$this->SenderEmail = createEmail("do-not-reply");
		$this->ReceiverEmail = createEmail("info");
		\MiMFa\Library\Revise::Load($this);
		
		$menu = between($this->MainMenus,$this->SideMenus,$this->Shortcuts,$this->Services);
		if(!$this->MainMenus) $this->MainMenus = $menu;
		if(!$this->SideMenus) $this->SideMenus = $menu;
		if(!$this->Shortcuts) $this->Shortcuts = $menu;
		if(!$this->Services) $this->Services = $menu;
	}
	public function __get($name) {
        return $this[$this->PropertyName($name)];
    }
    public function __set($name, $value) {
        $this[$this->PropertyName($name)] = $value;
    }
    public function PropertyName($name) {
        return preg_replace("/\W+/", "", strToProper($name));
    }
}
?>