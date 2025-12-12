<?php
library("Session");
library("Contact");
library("Cryptograph");
library("SpecialCrypt");

use MiMFa\Library\Contact;
use MiMFa\Library\Convert;
use MiMFa\Library\Cryptograph;
use MiMFa\Library\SpecialCrypt;
use MiMFa\Library\DataTable;
use MiMFa\Library\Struct;

class UserBase
{
    /**
     * Full part of the current url
     * @example: "https://www.mimfa.net:5056/Category/mimfa/service/web.php?p=3&l=10#serp"
     * @var string|null
     */
    public string|null $Url = null;
    /**
     * The path part of the current url
     * @example: "https://www.mimfa.net:5056/Category/mimfa/service/web.php"
     * @var string|null
     */
    public string|null $Path = null;
    /**
     * The host part of the current url
     * @example: "https://www.mimfa.net:5056"
     * @var string|null
     */
    public string|null $Host = null;
    /**
     * The site name part of the current url
     * @example: "www.mimfa.net"
     * @var string|null
     */
    public string|null $Site = null;
    /**
     * The domain name part of the current url
     * @example: "mimfa.net"
     * @var string|null
     */
    public string|null $Domain = null;
    /**
     * The request part of the current url
     * @example: "/Category/mimfa/service/web.php?p=3&l=10#serp"
     * @var string|null
     */
    public string|null $Request = null;
    /**
     * The direction part of the current url from the root
     * @example: "Category/mimfa/service/web.php"
     * @var string|null
     */
    public string|null $Direction = null;
    /**
     * The last part of the current direction url
     * @example: "web.php"
     * @var string|null
     */
    public string|null $Page = null;
    /**
     * The query part of the current url
     * @example: "p=3&l=10"
     * @var string|null
     */
    public string|null $Query = null;
    /**
     * The fragment or anchor part of the current url
     * @example: "serp"
     * @var string|null
     */
    public string|null $Fragment = null;

	public $DefaultImagePath = "user";
	public $HandlerPath = "/sign";
	public $SignHandlerPath = "/sign/sign";
	public $UpHandlerPath = "/sign/up";
	public $InHandlerPath = "/sign/in";
	public $OutHandlerPath = "/sign/out";
	public $ProfileHandlerPath = "/sign/profile";
	public $EditHandlerPath = "/sign/edit";
	public $DashboardHandlerPath = "/sign/dashboard";
	public $RecoverHandlerPath = "/sign/recover";
	public $ActiveHandlerPath = "/sign/active";

	public $PasswordSecure = true;

	public $TokenDelimiter = "�";
	public $TokenDateTimeFormat = "Y/m/d";

	/**
	 * Direct activate or deactivate the user abilities
	 * @var mixed
	 */
	public $Active = true;
	/**
	 * Initial User Default Status:
	 *		true/1:		Activated
	 *		false/-1:		Deactivated
	 * @var int
	 */
	public $ActiveStatus = 1;
	public $InitialStatus = 0;
	public $DeactiveStatus = -1;


	/**
	 * Allow signing in and up to the guests
	 * @var bool
	 * @category Security
	 */
	public $AllowSigning = false;
	/**
	 * The minimum group id available to choice by user
	 * @default 100
	 * @var int
	 * @category Security
	 */
	public $MinimumGroupId = 100;
	/**
	 * The maximum group id available to choice by user
	 * @default 999999999
	 * @var int
	 * @category Security
	 */
	public $MaximumGroupId = 999999999;
	/**
	 * The minimum group of banned user
	 * @default -1
	 * @var int
	 * @category Security
	 */
	public $BanAccess = -1;
	/**
	 * Default accessibility for the guests
	 * @default 0
	 * @var int
	 * @category Security
	 */
	public $GuestAccess = 0;
	/**
	 * The lowest group that registered user will be on
	 * @default 1
	 * @var int
	 * @category Security
	 */
	public $UserAccess = 1;
	/**
	 * The lowest group of administrators
	 * @default 988888888
	 * @var int
	 * @category Security
	 */
	public $AdminAccess = 988888888;
	/**
	 * The highest group of administrators
	 * @default 999999999
	 * @var int
	 * @category Security
	 */
	public $SuperAccess = 999999999;
	/**
	 * Minimum accessibility needs to visit the website
	 * @default 0
	 * @var int
	 * @category Security
	 */
	public $VisitAccess = 0;


	public $Id = null;
	public $GroupId = null;
	protected $Access = 0;
	protected $Accesses = array();
	public $Authorize;

	public $Signature = null;
	public $Image = null;
	public $Name = null;
	public $Email = null;

	public $TemporarySignature = null;
	private $TemporaryPassword = null;
	public $TemporaryImage = null;
	public $TemporaryName = null;
	public $TemporaryEmail = null;


	public DataTable $DataTable;
	public DataTable $GroupDataTable;
	public Cryptograph $Cryptograph;

	/**
	 * A simple library to Session management
	 * @internal
	 */
	public \MiMFa\Library\Session $Session;
	/**
	 * Allow to set sessions on the client side (false for default)
	 * @var bool
	 * @category Security
	 */
	public $AccessibleData = true;
	/**
	 * Encrypt all session keys (false for default)
	 * @var bool
	 * @category Security
	 */
	public $EncryptKey = false;
	/**
	 * Encrypt all session values (true for default)
	 * @var bool
	 * @category Security
	 */
	public $EncryptValue = true;


	public function __construct()
	{
		$this->Cryptograph = new SpecialCrypt();
		$this->Session = new \MiMFa\Library\Session(new DataTable(\_::$Back->DataBase, "Session", \_::$Back->DataBasePrefix), $this->Cryptograph);
		$this->Session->AccessibleData = $this->AccessibleData;
		$this->Session->EncryptKey = $this->EncryptKey;
		$this->Session->EncryptValue = $this->EncryptValue;

        $this->Url = getUrl();
        $this->Host = getHost();
        $this->Site = getSite();
        $this->Domain = getDomain();
        $this->Path = getPath();
        $this->Request = getRequest();
        $this->Direction = getDirection();
        $this->Page = getPage();
        $this->Query = getQuery();
        $this->Fragment = getFragment();

		$this->Access = $this->GuestAccess;
		$this->Authorize = fn() => load($this->InHandlerPath);

		$this->DataTable = table("User");
		$this->GroupDataTable = table("UserGroup");

		if ($this->Active) $this->Refresh();
		\MiMFa\Library\Revise::Decode($this, takeValid($this->GetGroup(), "MetaData", "[]"));
		\MiMFa\Library\Revise::Decode($this, takeValid($this->Get(), "MetaData", "[]"));
	}

	public function Refresh()
	{
		$this->TemporarySignature = $this->Session->GetSecure("Signature") ?? $this->TemporarySignature;
		$this->TemporaryPassword = $this->Session->GetSecure("Password") ?? $this->TemporaryPassword;
		try {
			if (
				$this->TemporarySignature === $this->Session->GetData($this->TemporarySignature . "_" . getClientCode()) &&
				isValid($this->TemporarySignature) &&
				isValid($this->TemporaryPassword)
			)
				$this->Load($this->Find($this->TemporarySignature, $this->TemporaryPassword, false));
			else
				$this->Load();
		} catch (\Exception) {
			$this->SignOut();
		}
		return !is_null($this->Id);
	}
	public function Load($profile = null)
	{
		$this->Session->SetSecure("Signature", $this->Signature = $this->TemporarySignature = takeValid($profile, "Signature"));
		$this->Session->SetSecure("Password", $this->TemporaryPassword = takeValid($profile, "Password"));
		$this->Session->SetSecure("Id", $this->Id = takeValid($profile, "Id"));
		$this->Session->SetSecure("GroupId", $this->GroupId = takeValid($profile, "GroupId"));
		$this->Session->SetSecure("Image", $this->Image = $this->TemporaryImage = takeValid($profile, "Image"));
		$this->Session->SetSecure("Name", $this->Name = $this->TemporaryName = takeValid($profile, "Name"));
		$this->Session->SetSecure("Email", $this->Email = $this->TemporaryEmail = takeValid($profile, "Email"));
		$this->Session->SetSecure("Access", $this->Access = is_null($this->GroupId) ? null : $this->GroupDataTable->SelectValue("`Access`", "Id=" . $this->GroupId));
		if (!$profile) {
			$this->Session->PopSecure("Signature");
			$this->Session->PopSecure("Password");
			$this->Session->PopSecure("Id");
			$this->Session->PopSecure("GroupId");
			$this->Session->PopSecure("Image");
			$this->Session->PopSecure("Name");
			$this->Session->PopSecure("Email");
			$this->Session->PopSecure("Access");
		}
	}

	/**
	 * To check if the user has access to the page or not
	 * @param int|array|null $acceptableAccess The minimum accessibility for the user, pass null to give the user access
	 * @return bool|null Returns true if the user has access, false if not, and null if undetermined
	 */
	public function HasAccess($acceptableAccess = null)
	{
		if (is_null($acceptableAccess))
			$acceptableAccess = $this->UserAccess;
		$access = $this->Access ?? $this->GuestAccess;
		//if($acceptableAccess === true || $acceptableAccess === false) return $acceptableAccess;
		if (is_int($acceptableAccess))
			return $access >= $acceptableAccess;
		if (is_array($acceptableAccess) && count($acceptableAccess) > 0)
			if (getBetween($acceptableAccess, "Min", "Max", "Include", "Exclude") ?? false)
				return (get($acceptableAccess, "Min") ?? $access) <= $access
					&& (get($acceptableAccess, "Max") ?? $access) >= $access
					&& !in_array($access, get($acceptableAccess, "Exclude") ?? [])
					&& in_array($access, get($acceptableAccess, "Include") ?? [$access]);
			else
				return in_array($access, $acceptableAccess);
		return null;
	}
	/**
	 * To get the current user access level
	 * @return int Returns the current user access level
	 */
	public function GetAccess()
	{
		return $this->Access ?? $this->GuestAccess;
	}
	public function GetAccessCondition($tablePrefix = "")
	{
		return /*"$tableName`Access`>=" . $this->VisitAccess." OR ".*/ "{$tablePrefix}Access<=" . $this->GetAccess();
	}

	public function Find($signature = null, $password = null, $hashPassword = true)
	{
		if (!$this->Active)
			return [];
		if (!isValid($signature = $signature ?? $this->Signature ?? $this->TemporarySignature)) {
			$this->SignOut($signature);
			return [];
		}
		if (isValid($password)) {
			if ($hashPassword)
				$password = $this->EncryptPassword($password);
			$person = $this->DataTable->SelectRow(
				"`Signature` , Id , `GroupId` , `Image` , `Name` , `Email` , `Password` , `Status`",
				"(Id=:Signature OR `Signature`=:Signature OR `Email`=:Email OR (`Contact` IS NOT NULL AND `Contact`=:Signature)) AND `Password`=:Password",
				[":Signature" => $signature, ":Email" => strtolower($signature), ":Password" => $password]
			);
			if (isEmpty($person)) {
				$this->SignOut($signature);
				throw new \SilentException("The username or password is incorrect!");
			}
		} else {
			$person = $this->DataTable->SelectRow(
				"`Signature` , Id , `GroupId` , `Image` , `Name` , `Email` , `Password` , `Status`",
				"Id=:Signature OR `Signature`=:Signature OR `Email`=:Email OR (`Contact` IS NOT NULL AND `Contact`=:Signature)",
				[":Signature" => $signature, ":Email" => strtolower($signature)]
			);
			if (isEmpty($person)) {
				$this->SignOut($signature);
				throw new \SilentException("The username is incorrect!");
			}
		}
		$this->TemporarySignature = takeValid($person, "Signature");
		$this->TemporaryImage = takeValid($person, "Image");
		$this->TemporaryName = takeValid($person, "Name");
		$this->TemporaryEmail = takeValid($person, "Email");
		return $person;
	}
	public function Get($signature = null, $password = null)
	{
		if (is_null($id = $this->Id) || !is_null($signature))
			$id = takeValid($this->Find($signature, $password), "Id");
		return is_null($id) ? null : takeValid($this->DataTable->Select("*", "Id=:Id", [":Id" => $id]), 0);
	}
	public function Set($fieldsDictionary, $signature = null, $password = null)
	{
		$id = $this->Id;
		$email = $this->Email;
		if (!is_null($signature) || is_null($id) || is_null($email)) {
			$person = $this->Find($signature, $password);
			$id = takeValid($person, "Id");
			$email = takeValid($person, "Email");
		}
		if (is_null($id))
			return null;
		if (takeValid($fieldsDictionary, "Email", $email) != $email)
			$fieldsDictionary["Status"] = $this->InitialStatus;
		return $this->DataTable->Update("Id='$id'", $fieldsDictionary);
	}
	public function GetValue($key, $signature = null, $password = null)
	{
		if (is_null($id = $this->Id) || !is_null($signature))
			$id = takeValid($this->Find($signature, $password), "Id");
		return is_null($id) ? null : $this->DataTable->SelectValue($key, "Id=$id");
	}
	public function SetValue($key, $value, $signature = null, $password = null)
	{
		if (is_null($id = $this->Id) || !is_null($signature))
			$id = takeValid($this->Find($signature, $password), "Id");
		return is_null($id) ? null : $this->DataTable->Update("Id=$id", [$key => $value]);
	}
	public function GetMetaValue($key, $signature = null, $password = null)
	{
		if (is_null($id = $this->Id) || !is_null($signature))
			$id = takeValid($this->Find($signature, $password), "Id");
		return is_null($id) ? null : $this->DataTable->GetMetaValue($id, $key);
	}
	public function SetMetaValue($key, $value, $signature = null, $password = null)
	{
		if (is_null($id = $this->Id) || !is_null($signature))
			$id = takeValid($this->Find($signature, $password), "Id");
		return is_null($id) ? null : $this->DataTable->SetMetaValue($id, $key, $value);
	}
	public function PopMetaValue($key, $signature = null, $password = null)
	{
		if (is_null($id = $this->Id) || !is_null($signature))
			$id = takeValid($this->Find($signature, $password), "Id");
		return is_null($id) ? null : $this->DataTable->PopMetaValue($id, $key);
	}

	public function GetGroup($signature = null, $password = null)
	{
		if (is_null($id = $this->GroupId) || !is_null($signature))
			$id = takeValid($this->Find($signature, $password), "GroupId");
		return is_null($id) ? null : takeValid($this->GroupDataTable->Select("*", "Id=:Id", [":Id" => $id]), 0);
	}
	public function SetGroup($fieldsDictionary, $signature = null, $password = null)
	{
		$id = $this->GroupId;
		if (!is_null($signature) || is_null($id)) {
			$person = $this->Find($signature, $password);
			$id = takeValid($person, "GroupId");
		}
		if (is_null($id))
			return null;
		return $this->GroupDataTable->Update("Id='$id'", $fieldsDictionary);
	}

	public function GenerateSign($slogan = "Kind Regards")
	{
		return trim(join(PHP_EOL, [
			$this->Access >= $this->AdminAccess ? \_::$Info->Slogan : "",
			$slogan ? "*$slogan*" : "",
			"------",
			"*" . $this->Name . "*",
			get($this->GetGroup(), "Title") . " at " . \_::$Info->Name,
			$this->Access >= $this->AdminAccess ? \_::$Info->ReceiverEmail ?? $this->Email : $this->Email,
			"[" . \_::$Info->FullName . "](" . \_::$Info->Path . ")"
		]));
	}
	public function GenerateEmail($name = null, $fake = false)
	{
		return Convert::ToKey($name ?? $this->Signature ?? ("user_" . getId(true))) . ($fake ? uniqid("+") : "") . "@" . $this->Domain;
	}

	/**
	 * Summary of SignUp
	 * @param mixed $signature
	 * @param mixed $password
	 * @param mixed $email
	 * @param mixed $name
	 * @param mixed $firstName
	 * @param mixed $lastName
	 * @param mixed $contact
	 * @param mixed $groupId
	 * @param mixed $status
	 * @param mixed $metadata
	 * @return bool|null It will return 'true' if signed up 'null' if the account is not active yet, and 'false' otherwise
	 */
	public function SignUp($signature, $password, $email = null, $name = null, $firstName = null, $lastName = null, $contact = null, $groupId = null, $status = null, $metadata = null)
	{
		$this->TemporaryImage = null;
		$email = $email ?: $this->GenerateEmail($signature, true);
		return $this->DataTable->Insert(
			[
				":Signature" => $this->TemporarySignature = $signature ?? $contact ?? $email,
				":Email" => $this->TemporaryEmail = strtolower($email),
				":Password" => $this->TemporaryPassword = $this->EncryptPassword($password),
				":Name" => $this->TemporaryName = $name ?? trim($firstName . " " . $lastName),
				":FirstName" => $firstName,
				":LastName" => $lastName,
				":Contact" => $contact,
				":GroupId" => $groupId ?? $this->GroupDataTable->SelectValue("Id", "Access=" . $this->UserAccess) ?? 0,
				":Status" => $status,
				":MetaData" => $metadata ? (is_string($metadata) ? $metadata : json_encode($metadata)) : null,
			]
		) ? (($status === false || ((int) $status) < $this->ActiveStatus) ? null : true) : false;
	}
	/**
	 * Summary of SignIn
	 * @param mixed $signature
	 * @param mixed $password  Required to check validation of password before,
	 * leave null to sign in user only by the "Signature"
	 * @return bool|null It will return 'true' if logged in 'null' if the account is not active yet, and 'false' otherwise
	 */
	public function SignIn($signature, $password = null)
	{
		if (!$password && !is_null($password))
			return false;
		$person = null;
		try {
			$person = $this->Find($signature, $password);
		} catch (\Exception $ex) {
			return false;
		}
		$status = takeValid($person, "Status", $this->InitialStatus);
		if ($status === false || intval($status) < $this->ActiveStatus)
			return null;
		$this->Load($person);
		return $this->Session->SetData($this->Signature . "_" . getClientCode(), $this->Signature) ? true : false;
	}
	/**
	 * Summary of SignInOrSignUp
	 * @param mixed $signature
	 * @param mixed $password
	 * @param mixed $email
	 * @return bool|null It will return 'true' if logged in 'null' if the account is not active yet, and 'false' otherwise
	 */
	public function SignInOrSignUp($signature, $password, $email = null, $name = null, $firstName = null, $lastName = null, $contact = null, $groupId = null, $status = null, $metadata = null)
	{
		return ($res = $this->SignIn($signature ?? $email, $password)) !== false ? $res :
			(
				($res = $this->SignUp($signature ?? $email, $password, $email, $name, $firstName, $lastName, $contact, $groupId, $status, $metadata)) === true ?
				$this->SignIn($signature ?? $email, $password) : $res
			);
	}
	public function SignOut($signature = null)
	{
		if ($signature === null || $signature === $this->Signature) {
			$signature = $signature ?? $this->Signature;
			$this->Session->PopData($signature . "_" . getClientCode());
			$this->Load();
		} else
			$this->Session->PopData($signature . "_" . getClientCode());
		return !$this->GetAccess();
	}

	public function ResetPassword($signature = null, $password = null)
	{
		return $this->SetValue("Password", $this->EncryptPassword($password), $signature);
	}
	public function EncryptPassword($password)
	{
		if ($this->PasswordSecure)
			return sha1($password); //More safe method
		else
			return $password;
	}
	public function DecryptPassword($password)
	{
		if ($this->PasswordSecure)
			return null;
		else
			return $password;
	}


	/**
	 * To create and store a new token from the value
	 * @param mixed $key A special key to store token
	 * @param mixed $value The desired value to tokenize
	 * @return string The created token
	 */
	public function EncryptToken($key, $value)
	{
		$this->Session->SetData($value, $key);
		return $this->Cryptograph->Encrypt($value . $this->TokenDelimiter . date($this->TokenDateTimeFormat), \_::$Back->SoftKey, true);
	}
	/**
	 * To get the value hided in the token, then forget stored data
	 * @param mixed $key A special key to store token
	 * @param mixed $token The created token
	 * @throws \ErrorException
	 * @return mixed The desired value inside the token
	 */
	public function DecryptToken($key, $token)
	{
		if (!$key || !$token)
			return null;
		list($sign, $date) = explode($this->TokenDelimiter, $this->Cryptograph->Decrypt($token, \_::$Back->SoftKey, true));
		if ($this->Session->GetData($sign) != $key)
			throw new \SilentException("Your request is invalid or used before!");
		if ($date != date($this->TokenDateTimeFormat))
			throw new \SilentException("Your request is expired!");
		$this->Session->PopData($sign);
		return get($this->Find($sign), "Signature");
	}

	public function SendTokenEmail($from, $to, $subject, $content, $linkAnchor = "Click Here", $handlerPath = null, $tokenKey = "sign")
	{
		if (!$to)
			throw new \SilentException("Please input your 'email address'!");
		$person = $this->DataTable->SelectRow("*", "`Email`=:Email", [":Email" => $to]);
		if (isEmpty($person))
			throw new \SilentException("Unfortunately the email address is incorrect!");
		$this->TemporaryEmail = takeValid($person, "Email");
		$this->TemporarySignature = takeValid($person, "Signature");
		$this->TemporaryName = takeValid($person, "Name");
		$this->TemporaryImage = takeValid($person, "Image");
		$this->TemporaryPassword = takeValid($person, "Password");
		$path = $this->Host . ($handlerPath ?? $this->HandlerPath) . "?" . $tokenKey . "=" . urlencode($this->EncryptToken($tokenKey, $this->TemporarySignature));
		$dic = array();
		$dic['$HYPERLINK'] = Struct::Link($linkAnchor, $path);
		$dic['$LINK'] = Struct::Link($path, $path);
		$dic['$PATH'] = $path;
		$dic['$SIGNATURE'] = $this->TemporarySignature;
		$subject = Convert::FromDynamicString($subject ?? "", $dic, true);
		$content = Convert::FromDynamicString($content ?? "", $dic, false);
		return Contact::SendHtmlEmail($from, $this->TemporaryEmail, __($subject), __($content)) ? true : false;
	}

}