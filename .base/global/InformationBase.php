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
     * A full version of descriptions about the website owner
     * @field strings
     * @var mixed
	 */
	public $FullOwnerDescription = null;
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
	public $LogoPath = "asset/logo/logo.png";
	/**
     * The website brand logo path
     * @field path
	 * @var mixed
	 */
	public $BrandLogoPath = "asset/logo/brand-logo.png";
	/**
     * The website full logo path
     * @field path
	 * @var mixed
	 */
	public $FullLogoPath = "asset/logo/full-logo.png";
	
	/**
	 * The main KeyWords of the website, these will effect on SEO and views
     * @field array
     * @var array
	 */
	public $KeyWords = [];

	public function __construct(){
		$this->SenderEmail = createEmail("do-not-reply");
		$this->ReceiverEmail = createEmail("info");
		\MiMFa\Library\Revise::Load($this);
	}
	public function __get($name) {
        return $this[$this->PropertyName($name)]??null;
    }
    public function __set($name, $value) {
        $this[$this->PropertyName($name)] = $value;
    }
    public function PropertyName($name) {
        return preg_replace("/\W+/", "", strToProper($name));
    }
}