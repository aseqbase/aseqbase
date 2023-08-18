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

	public static $RecoverEmailSubject = 'Account Recovery Request';
	public static $RecoverEmailContent = 'Hello dear $NAME,<br><br>
We received an account recovery request on $HOSTLINK for $EMAILLINK.<br>
This email address is associated with an account but no password is associated with it yet, so it can�t be used to log in.<br>
Please $HYPERLINK or the below link if you want to reset your password... else ignore this message.<br>$LINK<br><br>
With Respect,<br>$HOSTLINK<br>$HOSTEMAILLINK';
	public static $RecoverLinkAnchor = "CLICK ON THIS LINK";
	public static $ActiveHandlerPath = "/sign/active.php";
	public static $ActiveEmailSubject = "Account Activation Request";
	public static $ActiveEmailContent = 'Hello dear $NAME,<br><br>
We received an account activation request on $HOSTLINK for $EMAILLINK.<br>
Thank you for registration, This email address is associated with an account but is not activated yet, so it can�t be used to log in.<br>
Please $HYPERLINK or the below link to active your account!<br>$LINK<br><br>
With Respect,<br>$HOSTLINK<br>$HOSTEMAILLINK';
	public static $ActiveLinkAnchor = "CLICK ON THIS LINK";

	public static $RecoveryRequestKey = "rk";
	public static $ActiveRequestKey = "ak";

	public static $PasswordPattern = "/([\w\W]+){8,64}/";
	public static $PasswordTips = "The password should be more than eigh and less than 64 alphabetic and numeric characters.";

	public static $SeparatorSign = "�";
	public static $DateTimeSignFormat = "Y/m/d";
	/**
     * Initial User Default Status:
     *		true/1:		Activated
     *		false/-1:		Deactivated
     * @var int
     */
	public static $ActiveStatus = 1;
	public static $InitialStatus = 0;
	public static $DeactiveStatus = -1;


	protected $ID = null;
	protected $GroupID = null;
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
		$this->Refresh();
	}

	public function Load(){
		$this->Refresh();
		return $this->Profile = getValid(DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."User","*","`ID`=:ID",[":ID"=>$this->ID]),0);
	}
	public function Refresh(){
		$this->Signature = Session::GetSecure("Signature");
		$this->Password = Session::GetSecure("Password");
		$person = null;
		try{
            if(isValid($this->Signature) && isValid($this->Password))
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
     * @param int|null $minaccess The minimum accessibility for the user, pass null to give the user access
     * @return int|mixed The user accessibility group
     */
	public function Access($minaccess=null){
		if(is_null($minaccess)) return $this->Access??\_::$CONFIG->GuestAccess;
		if(is_integer($minaccess)) return $this->Access >= $minaccess;
		return in_array($minaccess, $this->Accesses);
	}

	public function Find($signature = null, $password = null, $hashPassword = true){
		$signature = $signature??$this->Signature??Session::GetSecure("Signature")??$this->TemporarySignature;
		if(isValid($password)) {
			if($hashPassword) $password = $this->CheckPassword($password);
			$person = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."User",
						"`Signature`, `ID`, `GroupID`, `Image`, `Name`, `Email`, `Password`, `Status`",
						"(`Signature`=:Signature OR `Email`=:Email OR (`Contact` IS NOT NULL AND `Contact`=:Contact)) AND `Password`=:Password",
						[":Signature"=>$signature,":Email"=>$signature,":Contact"=>$signature,":Password"=> $password]
					);
            if(is_null($person)) throw new \ErrorException("There a problem is occured!");
            if(count($person) < 1) throw new \ErrorException("The username or password is incorrect!");
        } else {
			$person = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."User",
						"`Signature`, `ID`, `GroupID`, `Image`, `Name`, `Email`, `Password`, `Status`",
						"(`Signature`=:Signature OR `Email`=:Email OR (`Contact` IS NOT NULL AND `Contact`=:Contact))",
						[":Signature"=>$signature,":Email"=>$signature,":Contact"=>$signature]
					);
            if(is_null($person)) throw new \ErrorException("There a problem is occured!");
            if(count($person) < 1) throw new \ErrorException("The username is incorrect!");
        }
		$person = $person[0];
		$this->TemporarySignature = getValid($person,"Signature");
		$this->TemporaryImage = getValid($person,"Image");
		$this->TemporaryName = getValid($person,"Name");
		$this->TemporaryEmail = getValid($person,"Email");
		return $person;
	}
	public function Get($signature = null, $password = null){
		$person = $this->Find($signature, $password);
		return getValid(DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."User","*","`ID`=:ID",[":ID"=>$person["ID"]]),0);
    }
	public function Set($fieldsDictionary, $signature = null, $password = null){
		$person = $this->Find($signature, $password);
		if(getValid($fieldsDictionary, "Email", getValid($person,"Email"))!=getValid($person,"Email"))
			$fieldsDictionary["Status"] = self::$InitialStatus;
		return DataBase::DoUpdate(\_::$CONFIG->DataBasePrefix."User","`ID`='{$person["ID"]}'",$fieldsDictionary);
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
		$status = getValid($person,"Status",self::$InitialStatus);
		if($status === false || ((int)$status) < self::$ActiveStatus)
			throw new \ErrorException(
				"This account is not active yet!<br>".
				"<a href='".\MiMFa\Library\User::$ActiveHandlerPath."?signature=$signature'>".__("Try to send the activation email again!")."</a>"
			);
		Session::SetSecure("Password", $this->Password = getValid($person,"Password"));
		Session::SetSecure("Signature", $this->Signature = getValid($person,"Signature"));
		Session::SetSecure("ID",$this->ID = getValid($person,"ID"));
		Session::SetSecure("GroupID",$this->GroupID = getValid($person,"GroupID"));
		Session::SetSecure("Image",$this->Image = getValid($person,"Image"));
		Session::SetSecure("Name",$this->Name = getValid($person,"Name"));
		Session::SetSecure("Email",$this->Email = getValid($person,"Email"));
		Session::SetSecure("Access",$this->Access = DataBase::DoSelectValue(\_::$CONFIG->DataBasePrefix."UserGroup","`Access`","`ID`=".$this->GroupID));
		return true;
	}
	public function SignInOrSignUp($signature, $password, $email = null){
		return $this->SignIn($signature??$email, $password)??
			$this->SignUp($signature, $password, $email);
	}
	public function SignOut(){
		Session::Restart();
		$this->Load();
		return !self::Access(\_::$CONFIG->UserAccess);
	}

	public function ResetPassword($signature, $password){
		$password = $this->CheckPassword($password);
		return DataBase::DoUpdate(\_::$CONFIG->DataBasePrefix."User",
			"`Signature`=:Signature",
			[
				":Signature"=>$signature,
				":Password"=> $password
			]);
    }
	public function CheckPassword($password){
		if(preg_match(self::$PasswordPattern, $password)) return sha1($password);
        throw new \ErrorException(self::$PasswordTips);
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