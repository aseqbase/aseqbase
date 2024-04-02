<?php
namespace MiMFa\Library;
require_once "Session.php";
require_once "DataBase.php";
require_once "SpecialCrypt.php";
class User extends \Base{
	public static $HandlerPath = "/sign";
	public static $SignHandlerPath = "/sign/sign.php";
	public static $UpHandlerPath = "/sign/up.php";
	public static $InHandlerPath = "/sign/in.php";
	public static $OutHandlerPath = "/sign/out.php";
	public static $ViewHandlerPath = "/sign/view.php";
	public static $EditHandlerPath = "/sign/edit.php";
	public static $DashboardHandlerPath = "/sign/dashboard.php";
	public static $RecoverHandlerPath = "/sign/recover.php";
	public static $DefaultImagePath = "/file/symbol/user.png";

	public static $RecoverEmailSubject = 'Account Recovery Request';
	public static $RecoverEmailContent = 'Hello dear $NAME,<br><br>
We received an account recovery request on $HOSTLINK for $EMAILLINK.<br>
This email address is associated with an account but no password is associated with it yet, so it can’t be used to log in.<br>
Please $HYPERLINK or the below link if you want to reset your password... else ignore this message.<br>$LINK<br><br>
With Respect,<br>$HOSTLINK<br>$HOSTEMAILLINK';
	public static $RecoverLinkAnchor = "CLICK ON THIS LINK";
	public static $ActiveHandlerPath = "/sign/active.php";
	public static $ActiveEmailSubject = "Account Activation Request";
	public static $ActiveEmailContent = 'Hello dear $NAME,<br><br>
We received an account activation request on $HOSTLINK for $EMAILLINK.<br>
Thank you for registration, This email address is associated with an account but is not activated yet, so it can’t be used to log in.<br>
Please $HYPERLINK or the below link to active your account!<br>$LINK<br><br>
With Respect,<br>$HOSTLINK<br>$HOSTEMAILLINK';
	public static $ActiveLinkAnchor = "CLICK ON THIS LINK";

	public static $RecoveryRequestKey = "rk";
	public static $ActiveRequestKey = "ak";

	public static $PasswordSecure = true;
	public static $PasswordPattern = "/([\w\W]+){8,64}/";
	public static $PasswordTips = "The password should be more than eigh and less than 64 alphabetic and numeric characters.";

	public static $SeparatorSign = "¶";
	public static $DateTimeSignFormat = "Y/m/d";

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


	public $ID = null;
	public $GroupID = null;
	protected $Access = 0;
	protected $Accesses = array();

	private $Password = null;
	public $Signature = null;
	public $Image = null;
	public $Name = null;
	public $Email = null;

	public $TemporarySignature = null;
	public $TemporaryImage = null;
	public $TemporaryName = null;
	public $TemporaryEmail = null;

	public $Profile = null;

	public function __construct(){
		parent::__construct();
		Session::Start();
		if(self::$Active) $this->Refresh();
	}

	public function Load(){
		$this->Refresh();
		return $this->Profile = getValid(DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."User","*","`ID`=:ID",[":ID"=>$this->ID]),0);
	}
	public function Refresh(){
		$this->Signature = Session::GetSecure("Signature");
		$this->Password = Session::GetSecure("Password");
		$signature = Session::GetData("Signature_".$this->Signature);
		$person = null;
		try{
            if($signature === $this->Signature && isValid($this->Signature) && isValid($this->Password))
                $person = $this->Find($this->Signature, $this->Password, false);
            Session::SetSecure("Signature", $this->Signature = getValid($person,"Signature"));
            Session::SetSecure("ID",$this->ID = getValid($person,"ID"));
            Session::SetSecure("GroupID",$this->GroupID = getValid($person,"GroupID"));
            Session::SetSecure("Image",$this->Image = getValid($person,"Image"));
            Session::SetSecure("Name",$this->Name = getValid($person,"Name"));
            Session::SetSecure("Email",$this->Email = getValid($person,"Email"));
            Session::SetSecure("Access",$this->Access = is_null($this->GroupID)? null: DataBase::DoSelectValue(\_::$CONFIG->DataBasePrefix."UserGroup","`Access`","`ID`=".$this->GroupID));
        }catch(\Exception $ex){ $this->SignOut(); }
		return !is_null($this->ID);
    }

    /**
     * Check if the user has access to the page or not
     * @param int|array|null $acceptableAccess The minimum accessibility for the user, pass null to give the user access
     * @return int|mixed The user accessibility group
     */
	public function Access($acceptableAccess=null){
		if(is_null($acceptableAccess)) return ($this->Access)??\_::$CONFIG->GuestAccess;
		$acc = self::CheckAccess($this->Access, $acceptableAccess);
		if($acc) return true;
		foreach ($this->Accesses as $key=>$value)
            if($key = self::CheckAccess($value, $acceptableAccess))
                return $key;
        return $acc;
    }
    /**
     * Check if the user has access to the page or not
     * @param int|null $access The user access code, pass null to give the user access
     * @param int|array|null $acceptableAccess The minimum accessibility for the user, pass null to give the user access
     * @return int|mixed The user accessibility group
     */
	public static function CheckAccess($access=null, $acceptableAccess=null){
		if(is_null($access)) $access = \_::$CONFIG->GuestAccess;
		if(is_null($acceptableAccess)) return $access;
		if($acceptableAccess === true || $acceptableAccess === false) return $acceptableAccess;
		if(is_integer($acceptableAccess)) return $acceptableAccess > 0? ($access > 0? $access <= $acceptableAccess:false) : ($access >= $acceptableAccess);
		if(is_array($acceptableAccess) && count($acceptableAccess) > 0)
			if(isset($acceptableAccess["min"]) || isset($acceptableAccess["max"]))
				return (!isset($acceptableAccess["min"]) || $acceptableAccess["min"] <= $access) && (!isset($acceptableAccess["max"]) ||$acceptableAccess["max"] >= $access);
			else return in_array($access, $acceptableAccess);
		return null;
    }
	public static function GetAccessCondition(){
        $acc = getAccess();
        return "
				(`Status` IS NULL OR `Status` IN ('','1',1))
				AND `Access`>".\_::$CONFIG->BanAccess."
				AND (`Access`=".\_::$CONFIG->GuestAccess.($acc<=\_::$CONFIG->GuestAccess?"":" OR `Access`>=$acc").')'.
				(\_::$CONFIG->AllowTranslate?'AND (`MetaData` IS NULL OR `MetaData` NOT REGEXP \'/\s*(["\\\']?)lang\1\s*\:/i\' OR `MetaData` REGEXP \'/\s*(["\\\']?)lang\1\s*\:[\s\S]*([\"\\\'])'.Translate::$Language.'\2/i\')':'');
    }

	public function Find($signature = null, $password = null, $hashPassword = true){
		if(!self::$Active) return [];
		$signature = $signature??$this->Signature??Session::GetSecure("Signature")??$this->TemporarySignature;
		if(!isValid($signature)){
            $this->SignOut($signature);
            return [];
        }
		if(isValid($password)) {
			if($hashPassword) $password = $this->EncryptPassword($password);
			$person = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."User",
						"`Signature`, `ID`, `GroupID`, `Image`, `Name`, `Email`, `Password`, `Status`",
						"(`ID`=:ID OR `Signature`=:Signature OR `Email`=:Email OR (`Contact` IS NOT NULL AND `Contact`=:Contact)) AND `Password`=:Password",
						[":ID"=>$signature,":Signature"=>$signature,":Email"=>$signature,":Contact"=>$signature,":Password"=> $password]
					);
            if(is_null($person)){
				$this->SignOut($signature);
				throw new \ErrorException("There a problem is occured!");
            }
            if(count($person) < 1){
				$this->SignOut($signature);
				throw new \ErrorException("The username or password is incorrect!");
            }
        } else {
			$person = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."User",
						"`Signature`, `ID`, `GroupID`, `Image`, `Name`, `Email`, `Password`, `Status`",
						"(`ID`=:ID OR `Signature`=:Signature OR `Email`=:Email OR (`Contact` IS NOT NULL AND `Contact`=:Contact))",
						[":ID"=>$signature,":Signature"=>$signature,":Email"=>$signature,":Contact"=>$signature]
					);
            if(is_null($person)){
				$this->SignOut($signature);
				throw new \ErrorException("There a problem is occured!");
            }
            if(count($person) < 1){
				$this->SignOut($signature);
				throw new \ErrorException("The username is incorrect!");
            }
        }
		$person = $person[0];
		$this->TemporarySignature = getValid($person,"Signature");
		$this->TemporaryImage = getValid($person,"Image");
		$this->TemporaryName = getValid($person,"Name");
		$this->TemporaryEmail = getValid($person,"Email");
		return $person;
	}
	public function Get($signature = null, $password = null){
        if(is_null($id =$this->ID) || !is_null($signature)) $id = getValid($this->Find($signature, $password), "ID");
		return is_null($id)?null:getValid(DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."User","*","`ID`=:ID",[":ID"=>$id]),0);
    }
	public function Set($fieldsDictionary, $signature = null, $password = null){
        $id = $this->ID;
        $email = $this->Email;
		if(!is_null($signature) || is_null($id) || is_null($email)) {
			$person = $this->Find($signature, $password);
            $id = getValid($person,"ID");
            $email = getValid($person,"Email");
        }
		if(is_null($id)) return null;
		if(getValid($fieldsDictionary, "Email", $email)!=$email)
			$fieldsDictionary["Status"] = self::$InitialStatus;
		return DataBase::DoUpdate(\_::$CONFIG->DataBasePrefix."User","`ID`='$id'",$fieldsDictionary);
    }
	public function GetValue($key, $signature = null, $password = null){
        if(is_null($id = $this->ID) || !is_null($signature)) $id = getValid($this->Find($signature, $password), "ID");
		return is_null($id)?null:DataBase::DoSelectValue(\_::$CONFIG->DataBasePrefix."User",$key,"`ID`=$id");
    }
	public function SetValue($key, $value, $signature = null, $password = null){
        if(is_null($id =$this->ID) || !is_null($signature)) $id = getValid($this->Find($signature, $password), "ID");
		return is_null($id)?null:DataBase::DoUpdate(\_::$CONFIG->DataBasePrefix."User","`ID`=$id",[$key => $value]);
    }

	public function GetGroup($signature = null, $password = null){
        if(is_null($id =$this->GroupID) || !is_null($signature)) $id = getValid($this->Find($signature, $password), "GroupID");
		return is_null($id)?null:getValid(DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."UserGroup","*","`ID`=:ID",[":ID"=>$id]),0);
    }
	public function SetGroup($fieldsDictionary, $signature = null, $password = null){
        $id = $this->GroupID;
		if(!is_null($signature) || is_null($id)) {
			$person = $this->Find($signature, $password);
            $id = getValid($person,"GroupID");
        }
		if(is_null($id)) return null;
		return DataBase::DoUpdate(\_::$CONFIG->DataBasePrefix."UserGroup","`ID`='$id'",$fieldsDictionary);
    }

	public function SignUp($signature, $password, $email = null, $name = null, $firstName = null, $lastName = null, $phone = null, $groupID = null, $status = null){
		$password = $this->CheckPassword($password);
		$this->TemporaryImage = null;
		return DataBase::DoInsert(\_::$CONFIG->DataBasePrefix."User",null,
			[
				":Signature"=>$this->TemporarySignature = $signature??$email,
				":Email"=>$this->TemporaryEmail = $email,
				":Password"=> $password,
				":Name"=> $this->TemporaryName = $name?? trim($firstName." ".$lastName),
				":FirstName"=> $firstName,
				":LastName"=> $lastName,
				":Contact"=> $phone,
				":GroupID"=> $groupID??\_::$CONFIG->UserAccess,
				":Status"=> $status
			]);
	}
	public function SignIn($signature, $password){
		if(!isValid($password)) return false;
		$person = $this->Find($signature, $password);
		$status = getValid($person,"Status", self::$InitialStatus);
		if($status === false || ((int)$status) < self::$ActiveStatus)
			throw new \ErrorException(
				"This account is not active yet!<br>".
				"<a href='".self::$ActiveHandlerPath."?signature=$signature'>".__("Try to send the activation email again!")."</a>"
			);
		Session::SetData("Signature_".($this->Signature = getValid($person,"Signature")), $this->Signature = getValid($person,"Signature"));
		Session::SetSecure("Signature", $this->Signature);
		Session::SetSecure("Password", $this->Password = getValid($person,"Password"));
		Session::SetSecure("ID", $this->ID = getValid($person,"ID"));
		Session::SetSecure("GroupID", $this->GroupID = getValid($person,"GroupID"));
		Session::SetSecure("Image", $this->Image = getValid($person,"Image"));
		Session::SetSecure("Name", $this->Name = getValid($person,"Name"));
		Session::SetSecure("Email", $this->Email = getValid($person,"Email"));
		Session::SetSecure("Access", $this->Access = DataBase::DoSelectValue(\_::$CONFIG->DataBasePrefix."UserGroup","`Access`","`ID`=".$this->GroupID));
		return true;
	}
	public function SignInOrSignUp($signature, $password, $email = null){
		return $this->SignIn($signature??$email, $password)??
			$this->SignUp($signature, $password, $email);
	}
	public function SignOut($signature = null){
		if($signature === null || $signature === $this->Signature){
			Session::ForgetData("Signature");
            Session::ForgetSecure("Password");
            Session::ForgetSecure("Signature");
            Session::ForgetSecure("ID");
            Session::ForgetSecure("GroupID");
            Session::ForgetSecure("Image");
            Session::ForgetSecure("Name");
            Session::ForgetSecure("Email");
            Session::ForgetSecure("Access");
            $this->Load();
        } else Session::ForgetData($signature);
		return !self::Access(\_::$CONFIG->UserAccess);
	}

	public function ResetPassword($signature = null, $password = null){
		return $this->SetValue("Password", $this->CheckPassword($password), $signature);
    }
	public function CheckPassword($password){
		if(preg_match(self::$PasswordPattern, $password)) return self::EncryptPassword($password);
        throw new \ErrorException(self::$PasswordTips);
    }
	public function EncryptPassword($password){
		if(self::$PasswordSecure) return sha1($password); //More safe method
		else return $password;
    }
	public function DecryptPassword($password){
		if(self::$PasswordSecure) return null;
		else return $password;
    }

	public function ManageRequests(){
		if(isValid($_REQUEST,self::$ActiveRequestKey)) return $this->ReceiveActivationLink();
		if(isValid($_REQUEST,self::$RecoveryRequestKey)) return $this->ReceiveRecoveryLink();
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
	public function SendActivationEmail($emailFrom=null, $emailTo=null, $subject= null, $content=null, $linkAnchor = null){
		return $this->SendEmail($emailFrom??\_::$EMAIL, $emailTo??$this->TemporaryEmail, $subject??self::$ActiveEmailSubject, $content??self::$ActiveEmailContent, $linkAnchor??self::$ActiveLinkAnchor, self::$ActiveHandlerPath, self::$ActiveRequestKey);
	}
	/**
     * Receive the Activation Email and return the basic data of user
     * @return array<string>|null return the user Signature or false otherwise
     */
	public function ReceiveActivationEmail(){
        return $this->ReceiveEmail(self::$ActiveRequestKey);
    }
	/**
     * Receive the Activation Link and return the user Signature
     * @return bool|string return the user Signature or false otherwise
     */
	public function ReceiveActivationLink(){
		$sign = $this->ReceiveLink(self::$ActiveRequestKey);
		if(empty($sign)) return null;
		return DataBase::DoUpdate(\_::$CONFIG->DataBasePrefix."User",
			"`Signature`=:Signature",
			[
				":Signature"=>$sign,
				":Status"=> self::$ActiveStatus
			]);
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
	public function SendRecoveryEmail($emailFrom = null, $emailTo = null, $subject=null, $content=null, $linkAnchor = null){
		return $this->SendEmail($emailFrom??\_::$EMAIL, $emailTo??$this->TemporaryEmail, $subject??self::$RecoverEmailSubject, $content??self::$RecoverEmailContent, $linkAnchor??self::$RecoverLinkAnchor, self::$RecoverHandlerPath, self::$RecoveryRequestKey);
	}
	/**
     * Receive the Recovery Email and return the basic data of user
     * @return array<string>|null return the user Signature or false otherwise
     */
	public function ReceiveRecoveryEmail(){
        return $this->ReceiveEmail(self::$RecoveryRequestKey);
    }
	/**
     * Receive the Recovery Link and return the user Signature
     * @return bool|string return the user Signature or false otherwise
	 */
	public function ReceiveRecoveryLink(){
		$newPass = getValid($_REQUEST,"Password");
		if($newPass != getValid($_REQUEST, "PasswordConfirmation", $newPass)) throw new \ErrorException("New password and its confirmation does not match!");
		else if(isValid($newPass)){
			$this->CheckPassword($newPass);
            $sign = $this->ReceiveLink(self::$RecoveryRequestKey);
            if(empty($sign)) return null;
            return self::ResetPassword($sign, $newPass);
        }
		return false;
    }


	public function SendEmail($emailFrom, $emailTo, $subject, $content, $linkAnchor="Click Here", $handlerPath = null, $requestKey="key"){
		$person = getValid(DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."User","Signature","`Email`=:Email",[":Email"=> $emailTo]),0);
		if(is_null($person)) throw new \ErrorException("Unfortunately the email address is incorrect!");
		$sign = getValid($person,"Signature");
		Session::SetData($sign,$requestKey);
		$path = \_::$HOST.($handlerPath??self::$HandlerPath)."?".$requestKey."=".urlencode(SpecialCrypt::Encrypt($sign.self::$SeparatorSign.date(self::$DateTimeSignFormat),\_::$CONFIG->SecretKey, true));
		$dic = array();
		$dic['$HYPERLINK'] ="<a href='$path'>".__($linkAnchor)."</a>";
		$dic['$LINK'] ="<a href='$path'>$path</a>";
		$dic['$PATH'] =$path;
		$dic['$SIGNATURE'] = $sign;
		$subject = Convert::FromDynamicString($subject??"", $dic, true);
		$content = Convert::FromDynamicString($content??"", $dic, false);
		return Contact::SendHTMLEmail($emailFrom, $emailTo, __($subject), __($content));
	}
	public function ReceiveEmail($requestKey){
		$rp = getValid($_REQUEST, $requestKey);
		if(is_null($rp)) throw new \ErrorException("It is not a valid request!");
		$sign = SpecialCrypt::Decrypt($rp,\_::$CONFIG->SecretKey, true);
		list($sign, $date) = explode(self::$SeparatorSign, $sign);
		if(Session::GetData($sign) != $requestKey) throw new \ErrorException("Your request is invalid or used before!");
		if($date != date(self::$DateTimeSignFormat)) throw new \ErrorException("Your request is expired!");
        $person = self::Find($sign);
		return $person;
    }
	public function ReceiveLink($requestKey){
		$rp = getValid($_REQUEST, $requestKey);
		if(is_null($rp)) return false;
		$sign = SpecialCrypt::Decrypt($rp,\_::$CONFIG->SecretKey, true);
		list($sign, $date) = explode(self::$SeparatorSign, $sign);
		if(Session::GetData($sign) != $requestKey) throw new \ErrorException("Your request is invalid or used before!");
		if($date != date(self::$DateTimeSignFormat)) throw new \ErrorException("Your request is expired!");
		Session::ForgetData($sign);
        $person = self::Find($sign);
		return $person["Signature"];
    }
}
?>