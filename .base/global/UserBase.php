<?php
library("Contact");
library("DataBase");
library("DataTable");
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
	public $HandlerPath = "/sign";
	public $SignHandlerPath = "/sign/sign";
	public $UpHandlerPath = "/sign/up";
	public $InHandlerPath = "/sign/in";
	public $OutHandlerPath = "/sign/out";
	public $ProfileHandlerPath = "/sign/profile";
	public $EditHandlerPath = "/sign/edit";
	public $DashboardHandlerPath = "/sign/dashboard";
	public $RecoverHandlerPath = "/sign/recover";

	public $DefaultImagePath = "user";
	public $RecoverEmailSubject = 'Account Recovery Request';
	public $RecoverEmailContent = 'Hello dear $NAME,<br><br>
We received an account recovery request on $HOSTLINK for $EMAILLINK.<br>
This email address is associated with an account but no password is associated with it yet, so it can�t be used to log in.<br>
Please $HYPERLINK or the below link if you want to reset your password... else ignore this message.<br>$LINK<br><br>
With Respect,<br>$HOSTLINK<br>$HOSTEMAILLINK';
	public $RecoverLinkAnchor = "CLICK ON THIS LINK";
	public $ActiveHandlerPath = "/sign/active";
	public $ActiveEmailSubject = "Account Activation Request";
	public $ActiveEmailContent = 'Hello dear $NAME,<br><br>
We received an account activation request on $HOSTLINK for $EMAILLINK.<br>
Thank you for registration, This email address is associated with an account but is not activated yet, so it can�t be used to log in.<br>
Please $HYPERLINK or the below link to active your account!<br>$LINK<br><br>
With Respect,<br>$HOSTLINK<br>$HOSTEMAILLINK';
	public $ActiveLinkAnchor = "CLICK ON THIS LINK";

	public $RecoveryTokenKey = "rt";
	public $ActivationTokenKey = "at";

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


	public DataTable $DataTable;
	public DataTable $GroupDataTable;
	public Cryptograph $Cryptograph;


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

	public function __construct()
	{
		$this->Authorize = fn()=>load($this->InHandlerPath);
		$this->DataTable = table("User");
		$this->GroupDataTable = table("UserGroup");
		$this->Cryptograph = new SpecialCrypt();
		if ($this->Active)
			$this->Refresh();
		\MiMFa\Library\Revise::Decode($this, takeValid($this->GetGroup(), "MetaData", "[]"));
		\MiMFa\Library\Revise::Decode($this, takeValid($this->Get(), "MetaData", "[]"));
	}

	public function Refresh()
	{
		$this->TemporarySignature = \_::$Back->Session->GetSecure("Signature") ?? $this->TemporarySignature;
		$this->TemporaryPassword = \_::$Back->Session->GetSecure("Password") ?? $this->TemporaryPassword;
		try {
			if (
				$this->TemporarySignature === \_::$Back->Session->GetData($this->TemporarySignature . "_" . getClientCode()) &&
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
		\_::$Back->Session->SetSecure("Signature", $this->Signature = $this->TemporarySignature = takeValid($profile, "Signature"));
		\_::$Back->Session->SetSecure("Password", $this->TemporaryPassword = takeValid($profile, "Password"));
		\_::$Back->Session->SetSecure("Id", $this->Id = takeValid($profile, "Id"));
		\_::$Back->Session->SetSecure("GroupId", $this->GroupId = takeValid($profile, "GroupId"));
		\_::$Back->Session->SetSecure("Image", $this->Image = $this->TemporaryImage = takeValid($profile, "Image"));
		\_::$Back->Session->SetSecure("Name", $this->Name = $this->TemporaryName = takeValid($profile, "Name"));
		\_::$Back->Session->SetSecure("Email", $this->Email = $this->TemporaryEmail = takeValid($profile, "Email"));
		\_::$Back->Session->SetSecure("Access", $this->Access = is_null($this->GroupId) ? null : $this->GroupDataTable->SelectValue("`Access`", "Id=" . $this->GroupId));
		if (!$profile) {
			\_::$Back->Session->PopSecure("Signature");
			\_::$Back->Session->PopSecure("Password");
			\_::$Back->Session->PopSecure("Id");
			\_::$Back->Session->PopSecure("GroupId");
			\_::$Back->Session->PopSecure("Image");
			\_::$Back->Session->PopSecure("Name");
			\_::$Back->Session->PopSecure("Email");
			\_::$Back->Session->PopSecure("Access");
		}
	}

	/**
	 * Check if the user has access to the page or not
	 * @param int|array|null $acceptableAccess The minimum accessibility for the user, pass null to give the user access
	 * @return bool|int|null The user accessibility group
	 */
	public function GetAccess($acceptableAccess = null)
	{
		$access = $this->Access ?? $this->GuestAccess;
		if (is_null($acceptableAccess))
			return $access;
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
		return Convert::ToKey($name ?? $this->Signature ?? ("user_" . getId(true))) . ($fake ? uniqid("+") : "") . "@" . \_::$Address->Domain;
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
		$email = $email?:$this->GenerateEmail($signature);
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
		return \_::$Back->Session->SetData($this->Signature . "_" . getClientCode(), $this->Signature) ? true : false;
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
			\_::$Back->Session->PopData($signature . "_" . getClientCode());
			$this->Load();
		} else
			\_::$Back->Session->PopData($signature . "_" . getClientCode());
		return !$this->GetAccess($this->UserAccess);
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
	 * Send an Activation Email
	 * @param string $emailFrom Sender
	 * @param string $emailTo Receiver
	 * @param string The email text or html contents, contains:
	 * $HYPERLINK: for the Activation hyperlink tag
	 * $LINK: for the Activation link
	 * $PATH: for the Activation link address
	 * $NAME: for the user name
	 * $SIGNATURE: for the user Signature
	 * $IMAGE: for the user image path
	 */
	public function SendActivationEmail($receiverEmail = null, $subject = null, $content = null, $linkAnchor = null)
	{
		return $this->SendTokenEmail(\_::$Info->SenderEmail, $receiverEmail ?? $this->TemporaryEmail, $subject ?? $this->ActiveEmailSubject, $content ?? $this->ActiveEmailContent, $linkAnchor ?? $this->ActiveLinkAnchor, $this->ActiveHandlerPath, $this->ActivationTokenKey);
	}
	/**
	 * Receive the Activation Email
	 * @return bool|null return true if user activated, null if request is not received or false otherwise
	 */
	public function ReceiveActivationEmail()
	{
		$sign = $this->DecryptToken($this->ActivationTokenKey, getReceived($this->ActivationTokenKey));
		if (empty($sign))
			return null;
		return $this->DataTable->Update(
			"`Signature`=:Signature",
			[
				":Signature" => $sign,
				":Status" => $this->ActiveStatus
			]
		) ? true : false;
	}

	/**
	 * Send a Recovery Email
	 * @param string $emailFrom Sender
	 * @param string $emailTo Receiver
	 * @param string The email text or html contents, contains:
	 * $HYPERLINK: for the reset password hyperlink tag
	 * $LINK: for the reset password link
	 * $PATH: for the reset password link address
	 * $NAME: for the user name
	 * $SIGNATURE: for the user Signature
	 * $IMAGE: for the user image path
	 * @return bool
	 */
	public function SendRecoveryEmail($receiverEmail = null, $subject = null, $content = null, $linkAnchor = null)
	{
		return $this->SendTokenEmail(\_::$Info->SenderEmail, $receiverEmail ?? $this->TemporaryEmail, $subject ?? $this->RecoverEmailSubject, $content ?? $this->RecoverEmailContent, $linkAnchor ?? $this->RecoverLinkAnchor, $this->RecoverHandlerPath, $this->RecoveryTokenKey);
	}
	/**
	 * Receive the Recovery Email
	 * @return bool|null return true if user activated, null if request is not received or false otherwise
	 */
	public function ReceiveRecoveryEmail()
	{
		$newPass = getReceived("Password");
		if (isValid($newPass)) {
			$this->EncryptPassword($newPass);
			$sign = $this->DecryptToken($this->RecoveryTokenKey, getReceived($this->RecoveryTokenKey));
			if (empty($sign))
				return null;
			return $this->ResetPassword($sign, $newPass) ? true : false;
		}
		return null;
	}


	public function SendTokenEmail($from, $to, $subject, $content, $linkAnchor = "Click Here", $handlerPath = null, $tokenKey = "sign")
	{
		if (!$to)
			throw new \SilentException("Please indicate your email address!");
		$person = $this->DataTable->SelectRow("*", "`Email`=:Email", [":Email" => $to]);
		if (isEmpty($person))
			throw new \SilentException("Unfortunately the email address is incorrect!");
		$this->TemporaryEmail = takeValid($person, "Email");
		$this->TemporarySignature = takeValid($person, "Signature");
		$this->TemporaryName = takeValid($person, "Name");
		$this->TemporaryImage = takeValid($person, "Image");
		$this->TemporaryPassword = takeValid($person, "Password");
		$path = \_::$Address->Host . ($handlerPath ?? $this->HandlerPath) . "?" . $tokenKey . "=" . urlencode($this->EncryptToken($tokenKey, $this->TemporarySignature));
		$dic = array();
		$dic['$HYPERLINK'] = Struct::Link($linkAnchor, $path);
		$dic['$LINK'] = Struct::Link($path, $path);
		$dic['$PATH'] = $path;
		$dic['$SIGNATURE'] = $this->TemporarySignature;
		$subject = Convert::FromDynamicString($subject ?? "", $dic, true);
		$content = Convert::FromDynamicString($content ?? "", $dic, false);
		return Contact::SendHtmlEmail($from, $this->TemporaryEmail, __($subject), __($content));
	}

	/**
	 * To create and store a new token from the value
	 * @param mixed $key A special key to store token
	 * @param mixed $value The desired value to tokenize
	 * @return string The created token
	 */
	public function EncryptToken($key, $value)
	{
		\_::$Back->Session->SetData($value, $key);
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
		if (\_::$Back->Session->GetData($sign) != $key)
			throw new \SilentException("Your request is invalid or used before!");
		if ($date != date($this->TokenDateTimeFormat))
			throw new \SilentException("Your request is expired!");
		\_::$Back->Session->PopData($sign);
		return get($this->Find($sign), "Signature");
	}
}