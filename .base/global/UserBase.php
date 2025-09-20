<?php
library("DataBase");
library("DataTable");
library("Cryptograph");
library("Session");
library("SpecialCrypt");

use MiMFa\Library\Contact;
use MiMFa\Library\Convert;
use MiMFa\Library\Cryptograph;
use MiMFa\Library\DataTable;
use MiMFa\Library\Html;
use MiMFa\Library\Session;
use MiMFa\Library\SpecialCrypt;

class UserBase
{
	public static $HandlerPath = "/sign";
	public static $SignHandlerPath = "/sign/sign";
	public static $UpHandlerPath = "/sign/up";
	public static $InHandlerPath = "/sign/in";
	public static $OutHandlerPath = "/sign/out";
	public static $RouteHandlerPath = "/sign/profile";
	public static $EditHandlerPath = "/sign/edit";
	public static $DashboardHandlerPath = "/sign/dashboard";
	public static $RecoverHandlerPath = "/sign/recover";
	
	public static $DefaultImagePath = "user";
	public static $RecoverEmailSubject = 'Account Recovery Request';
	public static $RecoverEmailContent = 'Hello dear $NAME,<br><br>
We received an account recovery request on $HOSTLINK for $EMAILLINK.<br>
This email address is associated with an account but no password is associated with it yet, so it can�t be used to log in.<br>
Please $HYPERLINK or the below link if you want to reset your password... else ignore this message.<br>$LINK<br><br>
With Respect,<br>$HOSTLINK<br>$HOSTEMAILLINK';
	public static $RecoverLinkAnchor = "CLICK ON THIS LINK";
	public static $ActiveHandlerPath = "/sign/active";
	public static $ActiveEmailSubject = "Account Activation Request";
	public static $ActiveEmailContent = 'Hello dear $NAME,<br><br>
We received an account activation request on $HOSTLINK for $EMAILLINK.<br>
Thank you for registration, This email address is associated with an account but is not activated yet, so it can�t be used to log in.<br>
Please $HYPERLINK or the below link to active your account!<br>$LINK<br><br>
With Respect,<br>$HOSTLINK<br>$HOSTEMAILLINK';
	public static $ActiveLinkAnchor = "CLICK ON THIS LINK";

	public static $RecoveryTokenKey = "rt";
	public static $ActivationTokenKey = "at";

	public static $PasswordSecure = true;

	public static $TokenDelimiter = "�";
	public static $TokenDateTimeFormat = "Y/m/d";

	/**
	 * Direct activeate or deactivate the user abilities
	 * @var mixed
	 */
	public static $Active = true;
	/**
	 * Initial User Default Status:
	 *		true/1:		Activated
	 *		false/-1:		Deactivated
	 * @var int
	 */
	public static $ActiveStatus = 1;
	public static $InitialStatus = 0;
	public static $DeactiveStatus = -1;


	public $Id = null;
	public $GroupId = null;
	protected $Access = 0;
	protected $Accesses = array();

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
	public Session $Session;
	public Cryptograph $Cryptograph;

	public function __construct()
	{
		$this->DataTable = table("User", source:\_::$Back->DataBase);
		$this->GroupDataTable = table("UserGroup", source:\_::$Back->DataBase);
		$this->Session = \_::$Back->Session;
		$this->Cryptograph = new SpecialCrypt();
		if (self::$Active) $this->Refresh();
		\MiMFa\Library\Revise::Decode($this, takeValid($this->GetGroup(), "MetaData" , "[]"));
		\MiMFa\Library\Revise::Decode($this, takeValid($this->Get(), "MetaData" , "[]"));
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
				$this->Load(null);
		} catch (\Exception $ex) {
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
		$this->Session->SetSecure("Access", $this->Access = is_null($this->GroupId) ? null : $this->GroupDataTable->SelectValue("`Access`", "`Id`=" . $this->GroupId));
		if (!$profile) {
			$this->Session->ForgetSecure("Password");
			$this->Session->ForgetSecure("Signature");
			$this->Session->ForgetSecure("Id");
			$this->Session->ForgetSecure("GroupId");
			$this->Session->ForgetSecure("Image");
			$this->Session->ForgetSecure("Name");
			$this->Session->ForgetSecure("Email");
			$this->Session->ForgetSecure("Access");
		}
	}

	/**
	 * Check if the user has access to the page or not
	 * @param int|array|null $acceptableAccess The minimum accessibility for the user, pass null to give the user access
	 * @return bool|int|null The user accessibility group
	 */
	public function Access($acceptableAccess = null)
	{
		return self::CheckAccess($this->Access ?? \_::$Config->GuestAccess, $acceptableAccess);
	}
	/**
	 * Check if the user has access to the page or not
	 * @param int|null $access The user access code, pass null to give the user access
	 * @param int|array|null $acceptableAccess The minimum accessibility for the user, pass null to give the user access
	 * @return bool|int|null The user accessibility group
	 */
	public static function CheckAccess($access = null, $acceptableAccess = null)
	{
		if (is_null($access))
			$access = \_::$Config->GuestAccess;
		if (is_null($acceptableAccess)) return $access;
		//if($acceptableAccess === true || $acceptableAccess === false) return $acceptableAccess;
		if (is_int($acceptableAccess))
			return $access >= $acceptableAccess;
		if (is_array($acceptableAccess) && count($acceptableAccess) > 0)
			if (getBetween($acceptableAccess, "Min", "Max", "Include", "Exclude")??false)
				return (get($acceptableAccess, "Min")??$access) <= $access
					&& (get($acceptableAccess, "Max")??$access) >= $access
					&& !in_array($access, get($acceptableAccess, "Exclude")??[])
					&& in_array($access, get($acceptableAccess, "Include")??[$access]);
			else
				return in_array($access, $acceptableAccess);
		return null;
	}
	public function GetAccessCondition($tablePrefix = "")
	{
		return /*"$tableName`Access`>=" . \_::$Config->VisitAccess." OR ".*/"{$tablePrefix}Access<=" . $this->Access();
	}

	public function Find($signature = null, $password = null, $hashPassword = true)
	{
		if (!self::$Active)
			return [];
		if (!isValid($signature = $signature ?? $this->Signature ?? $this->TemporarySignature)) {
			$this->SignOut(signature: $signature);
			return [];
		}
		if (isValid($password)) {
			if ($hashPassword)
				$password = $this->EncryptPassword($password);
			$person = $this->DataTable->SelectRow(
				"`Signature` , `Id` , `GroupId` , `Image` , `Name` , `Email` , `Password` , `Status`",
				"(`Id`=:Id OR `Signature`=:Signature OR `Email`=:Email OR (`Contact` IS NOT NULL AND `Contact`=:Contact)) AND `Password`=:Password",
				[":Id" => $signature, ":Signature" => $signature, ":Email" => strtolower($signature), ":Contact" => $signature, ":Password" => $password]
			);
			if (isEmpty($person)) {
				$this->SignOut($signature);
				throw new \SilentException("The username or password is incorrect!");
			}
		} else {
			$person = $this->DataTable->SelectRow(
				"`Signature` , `Id` , `GroupId` , `Image` , `Name` , `Email` , `Password` , `Status`",
				"`Id`=:Id OR `Signature`=:Signature OR `Email`=:Email OR (`Contact` IS NOT NULL AND `Contact`=:Contact)",
				[":Id" => $signature, ":Signature" => $signature, ":Email" => strtolower($signature), ":Contact" => $signature]
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
		return is_null($id) ? null : takeValid($this->DataTable->Select("*", "`Id`=:Id", [":Id" => $id]), 0);
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
			$fieldsDictionary["Status"] = self::$InitialStatus;
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
		return is_null($id) ? null : $this->DataTable->Update("`Id`=$id", [$key => $value]);
	}
	public function GetMetaValue($key, $signature = null, $password = null)
	{
		if (is_null($id = $this->Id) || !is_null($signature))
			$id = takeValid($this->Find($signature, $password), "Id");
		return is_null($id) ? null : $this->DataTable->GetMetaValue($key, "`Id`=$id");
	}
	public function SetMetaValue($key, $value, $signature = null, $password = null)
	{
		if (is_null($id = $this->Id) || !is_null($signature))
			$id = takeValid($this->Find($signature, $password), "Id");
		return is_null($id) ? null : $this->DataTable->SetMetaValue($key, $value, "`Id`=$id");
	}
	public function ForgetMetaValue($key, $signature = null, $password = null)
	{
		if (is_null($id = $this->Id) || !is_null($signature))
			$id = takeValid($this->Find($signature, $password), "Id");
		return is_null($id) ? null : $this->DataTable->ForgetMetaValue($key, "`Id`=$id");
	}

	public function GetGroup($signature = null, $password = null)
	{
		if (is_null($id = $this->GroupId) || !is_null($signature))
			$id = takeValid($this->Find($signature, $password), "GroupId");
		return is_null($id) ? null : takeValid($this->GroupDataTable->Select("*", "`Id`=:Id", [":Id" => $id]), 0);
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
		return $this->GroupDataTable->Update("`Id`='$id'", $fieldsDictionary);
	}

	public function MakeSign($regards = "Kind Regards"){
		return trim(join("\n\r",[
				$this->Access >= \_::$Config->AdminAccess?\_::$Info->Slogan:"",
				$regards?"*$regards*":"",
                "------",
                "*".$this->Name."*",
                get($this->GetGroup(), "Title")." at ".\_::$Info->Name,
				$this->Access >= \_::$Config->AdminAccess?\_::$Info->ReceiverEmail??$this->Email:$this->Email,
                "[".\_::$Info->FullName."](".\_::$Info->Path.")"
			]));
	}

	/**
	 * Summary of SignUp
	 * @param mixed $signature
	 * @param mixed $password
	 * @param mixed $email
	 * @param mixed $name
	 * @param mixed $firstName
	 * @param mixed $lastName
	 * @param mixed $phone
	 * @param mixed $groupId
	 * @param mixed $status
	 * @param mixed $metadata
	 * @return bool|null It will return 'true' if signed up 'null' if the account is not active yet, and 'false' otherwise
	 */
	public function SignUp($signature, $password, $email = null, $name = null, $firstName = null, $lastName = null, $phone = null, $groupId = null, $status = null, $metadata = null)
	{
		$this->TemporaryImage = null;
		return $this->DataTable->Insert([
				":Signature" => $this->TemporarySignature = $signature ?? $email,
				":Email" => $this->TemporaryEmail = strtolower($email),
				":Password" => $this->TemporaryPassword = $this->EncryptPassword($password),
				":Name" => $this->TemporaryName = $name ?? trim($firstName . " " . $lastName),
				":FirstName" => $firstName,
				":LastName" => $lastName,
				":Contact" => $phone,
				":GroupId" => $groupId ?? $this->GroupDataTable->SelectValue("`Id`", "Access=" . \_::$Config->UserAccess) ?? 0,
				":Status" => $status,
				":MetaData" => $metadata ? (is_string($metadata) ? $metadata : json_encode($metadata)) : null,
			]
		)?(($status === false || ((int) $status) < self::$ActiveStatus)?null:true):false;
	}
	/**
	 * Summary of SignIn
	 * @param mixed $signature
	 * @param mixed $password
	 * @return bool|null It will return 'true' if logged in 'null' if the account is not active yet, and 'false' otherwise
	 */
	public function SignIn($signature, $password)
	{
		if (!isValid($password))
			return false;
		$person = null;
		try{ $person = $this->Find($signature, $password); } catch(\Exception $ex) { return false; }
		$status = takeValid($person, "Status", self::$InitialStatus);
		if ($status === false || intval($status) < self::$ActiveStatus)
			return null;
		$this->Load($person);
		$this->Session->SetData($this->Signature . "_" . getClientCode(), $this->Signature);
		return true;
	}
	/**
	 * Summary of SignInOrSignUp
	 * @param mixed $signature
	 * @param mixed $password
	 * @param mixed $email
	 * @return bool|null It will return 'true' if logged in 'null' if the account is not active yet, and 'false' otherwise
	 */
	public function SignInOrSignUp($signature, $password, $email = null)
	{
		return ($res = $this->SignIn($signature ?? $email, $password)) !== false ?$res:
			$this->SignUp($signature ?? $email, $password, $email);
	}
	public function SignOut($signature = null)
	{
		if ($signature === null || $signature === $this->Signature){
			$this->Session->ForgetData($signature . "_" . getClientCode());
			$this->Load(null);
		} else
			$this->Session->ForgetData($signature . "_" . getClientCode());
		return !self::Access(\_::$Config->UserAccess);
	}

	public function ResetPassword($signature = null, $password = null)
	{
		return $this->SetValue("Password", $this->EncryptPassword($password), $signature);
	}
	public function EncryptPassword($password)
	{
		if (self::$PasswordSecure)
			return sha1($password); //More safe method
		else
			return $password;
	}
	public function DecryptPassword($password)
	{
		if (self::$PasswordSecure)
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
	 * @return bool
	 */
	public function SendActivationEmail($receiverEmail = null, $subject = null, $content = null, $linkAnchor = null)
	{
		return $this->SendTokenEmail(\_::$Info->SenderEmail, $receiverEmail ?? $this->TemporaryEmail, $subject ?? self::$ActiveEmailSubject, $content ?? self::$ActiveEmailContent, $linkAnchor ?? self::$ActiveLinkAnchor, self::$ActiveHandlerPath, self::$ActivationTokenKey);
	}
	/**
	 * Receive the Activation Email
	 * @return bool|null return true if user activated, null if request is not received or false otherwise
	 */
	public function ReceiveActivationEmail()
	{
		$sign = $this->DecryptToken(self::$ActivationTokenKey, receive(self::$ActivationTokenKey));
		if (empty($sign))
			return null;
		return $this->DataTable->Update(
			"`Signature`=:Signature",
			[
				":Signature" => $sign,
				":Status" => self::$ActiveStatus
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
		return $this->SendTokenEmail(\_::$Info->SenderEmail, $receiverEmail ?? $this->TemporaryEmail, $subject ?? self::$RecoverEmailSubject, $content ?? self::$RecoverEmailContent, $linkAnchor ?? self::$RecoverLinkAnchor, self::$RecoverHandlerPath, self::$RecoveryTokenKey);
	}
	/**
	 * Receive the Recovery Email
	 * @return bool|null return true if user activated, null if request is not received or false otherwise
	 */
	public function ReceiveRecoveryEmail()
	{
		$newPass = receive("Password");
		if (isValid($newPass)) {
			$this->EncryptPassword($newPass);
			$sign = $this->DecryptToken(self::$RecoveryTokenKey, receive(self::$RecoveryTokenKey));
			if (empty($sign))
				return null;
			return self::ResetPassword($sign, $newPass) ? true : false;
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
		$path = \_::$Host . ($handlerPath ?? self::$HandlerPath) . "?" . $tokenKey . "=" . urlencode($this->EncryptToken($tokenKey, $this->TemporarySignature));
		$dic = array();
		$dic['$HYPERLINK'] = Html::Link($linkAnchor, $path);
		$dic['$LINK'] = Html::Link($path, $path);
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
		$this->Session->SetData($value, $key);
		return $this->Cryptograph->Encrypt($value . self::$TokenDelimiter . date(self::$TokenDateTimeFormat), \_::$Config->SoftKey, true);
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
		list($sign, $date) = explode(self::$TokenDelimiter, $this->Cryptograph->Decrypt($token, \_::$Config->SoftKey, true));
		if ($this->Session->GetData($sign) != $key)
			throw new \SilentException("Your request is invalid or used before!");
		if ($date != date(self::$TokenDateTimeFormat))
			throw new \SilentException("Your request is expired!");
		$this->Session->ForgetData($sign);
		return get(self::Find($sign), "Signature");
	}
}