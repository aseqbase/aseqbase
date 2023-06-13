<?php
namespace MiMFa\Library;
require_once "Session.php";
require_once "DataBase.php";
require_once "SpecialCrypt.php";
class User extends \Base{
	public static $HandlerPath = "/sign";
	public static $SignHandlerPath = "/sign/sign";
	public static $UpHandlerPath = "/sign/up";
	public static $InHandlerPath = "/sign/in";
	public static $OutHandlerPath = "/sign/out";
	public static $RememberHandlerPath = "/sign/remember";
	public static $ProfileHandlerPath = "/sign/profile";
	public static $ResetHandlerPath = "/sign/reset";
	public static $ActiveHandlerPath = "/sign/active";

	public static $ResetRequestKey = "resetkey";
	public static $ActiveRequestKey = "activekey";

	public static $PasswordPattern = "/[\w\W]{8,}/";
	public static $PasswordTips = "The password should be more than 8 alphabetic and numeric characters.";
	
	public static $SeparatorSign = "¶";
	public static $DateTimeSignFormat = "Y/m/d";
	/**
	 * Initial User Status:
     *		true/1:		Activated
     *		false/-1:		Deactivated
	 * @var bool
	 */
	public static $ActiveStatus = 1;

	protected $ID = null;
	protected $GroupID = null;
	protected $Access = 0;
	protected $Accesses = array();
	
	public $TemporaryName = null;
	public $TemporaryEmail = null;

	public $Image = null;
	public $Name = null;
	public $Email = null;

	public $Profile = null;

	public function __construct(){
		parent::__construct();
		Session::Start();
		$this->LoadAccess();
	}

	public function Load(){
		$this->LoadAccess();
		$this->LoadProfile();
	}
	public function LoadAccess(){
		$this->ID = Session::GetSecure("ID");
		$this->GroupID = Session::GetSecure("GroupID");
		$this->Access = Session::GetSecure("Access")??\_::$CONFIG->GuestAccess;
		$this->Image = Session::Get("Image");
		$this->Name = $this->TemporaryName = Session::Get("Name");
		$this->Email = $this->TemporaryEmail = Session::Get("Email");
		return !is_null($this->ID);
	}
	public function LoadProfile(){
		return $this->Profile = getValid(DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."User","*","`ID`=:ID",[":ID"=>$this->ID]),0);
	}

	public function Access($task=null){
		if(is_null($task)) return $this->Access??\_::$CONFIG->GuestAccess;
		if(is_integer($task)) return $this->Access >= $task;
		return in_array($task, $this->Accesses);
	}


	public function SignUp($signature, $password, $email = null, $name = null, $firstName = null, $lastName = null, $phone = null, $groupID = null, $status = null){
		$password = $this->CheckPassword($password);
		return DataBase::DoInsert(\_::$CONFIG->DataBasePrefix."User",null, 
			[
				":Signature"=>$signature??$email,
				":Email"=>$this->TemporaryEmail = $email,
				":Password"=> $password,
				":Name"=> $this->TemporaryName = $name?? trim($firstName." ".$lastName),
				":FirstName"=> $firstName,
				":LastName"=> $lastName,
				":Contact"=> $phone,
				":GroupID"=> $groupID??\_::$CONFIG->RegisteredGroup,
				":Status"=> $status
			]);
	}

	public function SignIn($signature, $password = null){
		$person;
		if(isValid($password)) {
			$password = $this->CheckPassword($password);
			$person = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."User",
						"`ID`, `GroupID`, `Image`, `Name`, `Email`, `Status`",
						"(`Signature`=:Signature OR `Email`=:Email OR (`Contact` IS NOT NULL AND `Contact`=:Contact)) AND `Password`=:Password",
						[":Signature"=>$signature,":Email"=>$signature,":Contact"=>$signature,":Password"=> $password]
					);
        } else {
			$person = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."User",
						"`ID`, `GroupID`, `Image`, `Name`, `Email`, `Status`",
						"(`Signature`=:Signature OR `Email`=:Email OR (`Contact` IS NOT NULL AND `Contact`=:Contact))",
						[":Signature"=>$signature,":Email"=>$signature,":Contact"=>$signature]
					);
        }
		if(is_null($person)) throw new \ErrorException("There a problem is occured!");
		if(count($person) < 1) throw new \ErrorException("The username or password is incorrect!");
		$person = $person[0];
		$status = getValid($person,"Status",0);
		if($status === false || ((int)$status) < self::$ActiveStatus)
			throw new \ErrorException(
				"This account is not active yet!<br>".
				"<a href='".\MiMFa\Library\User::$ActiveHandlerPath."'>".__("Try to send the activation email again!")."</a>"
			);
		Session::SetSecure("ID",$this->ID = getValid($person,"ID"));
		Session::SetSecure("GroupID",$this->GroupID = getValid($person,"GroupID"));
		Session::Set("Image",$this->Image = getValid($person,"Image"));
		Session::Set("Name",$this->Name = $this->TemporaryName = getValid($person,"Name"));
		Session::Set("Email",$this->Email = $this->TemporaryEmail = getValid($person,"Email"));
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
		return !Access(1);
	}
	
	public function CheckPassword($password){
		if(!preg_match(self::$PasswordPattern, $password))
			throw new \ErrorException(self::$PasswordTips);
		return sha1($password);
    }

	public function ManageRequests(){
		if(isValid($_REQUEST,self::$ActiveRequestKey)) return $this->ReceiveActivationLink();
		if(isValid($_REQUEST,self::$ResetRequestKey)) return $this->ReceiveResetPasswordLink();
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
	public function SendActivationEmail($emailFrom=null, $emailTo=null, $subject='Activation Request', $content='Hello dear $NAME<br>Thank you for registration, Please $HYPERLINK to Active Your Account!', $linkAnchor = "CLICK ON THIS LINK"){
		return $this->SendEmail($emailFrom??\_::$EMAIL, $emailTo??$this->TemporaryEmail, $subject, $content, $linkAnchor, self::$ActiveHandlerPath, self::$ActiveRequestKey);
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
				":Status"=> 1
			]);
    }

	/**
	 * Send a Reset Password Email
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
	public function SendResetPasswordEmail($emailFrom = null, $emailTo = null, $subject='Reset Password Request', $content='Hello dear $NAME<br>Please $HYPERLINK if you want to Reset your Password... else ignore this message.', $linkAnchor = "CLICK ON THIS LINK"){
		return $this->SendEmail($emailFrom??\_::$EMAIL, $emailTo??$this->TemporaryEmail, $subject, $content,$linkAnchor, self::$ResetHandlerPath, self::$ResetRequestKey);
	}
	/**
	 * Receive the Reset Password Link and return the user Signature
     * @return bool|string return the user Signature or false otherwise
	 */
	public function ReceiveResetPasswordLink($newPass = null){
		$sign =  $this->ReceiveLink(self::$ResetRequestKey);
		if(empty($sign)) return null;
		if(isValid($newPass)) {
			$newPass = $this->CheckPassword($newPass);
			return DataBase::DoUpdate(\_::$CONFIG->DataBasePrefix."User",
				"`Signature`=:Signature",
				[
					":Signature"=>$sign,
					":Password"=> $newPass
				]);
		}
		return $sign;
    }


	public function SendEmail($emailFrom, $emailTo, $subject, $content, $linkAnchor="Click Here", $handlerPath = null, $requestKey="key"){
		$person = getValid(DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."User","ID, GroupID, Image, Name, Signature, Email","`Email`=:Email",[":Email"=> $emailTo]),0);
		if(is_null($person)) throw new \ErrorException("Unfortunately the email address is incorrect!");
		$sign = getValid($person,"Signature");
		Session::SetData($sign,$requestKey);
		$path = \_::$HOST.($handlerPath??self::$HandlerPath)."?".$requestKey."=".urlencode(SpecialCrypt::Encrypt($sign.self::$SeparatorSign.date(self::$DateTimeSignFormat),\_::$CONFIG->SecretKey));
		$dic = array();
		$dic['$HYPERLINK'] ="<a href='$path'>".__($linkAnchor)."</a>";
		$dic['$LINK'] ="<a href='$path'></a>";
		$dic['$PATH'] =$path;
		$dic['$SIGNATURE'] =$sign;
		$dic['$NAME'] =getValid($person,"Name")??$this->TemporaryName;
		$dic['$EMAIL'] =getValid($person,"Email")??$this->TemporaryEmail;
		$dic['$IMAGE'] =getValid($person,"Image");
		$dic['$HOST'] =\_::$HOST;
		$subject = __($subject)??"";
		$content = __($content)??"";
		foreach ($dic as $key => $value){
			$key = $key??"";
			$value = $value??"";
            $subject = str_replace($key, $value, $subject);
            $content = str_replace($key, $value, $content);
        }
		return Contact::SendHTMLEmail($emailFrom, $emailTo, $subject, $content);
	}
	public function ReceiveLink($requestKey){
		$rp = getValid($_REQUEST, $requestKey);
		if(is_null($rp)) return false;
		$sign = SpecialCrypt::Decrypt(urldecode($rp),\_::$CONFIG->SecretKey);
		list($sign, $date) = explode(self::$SeparatorSign, $sign);
		if(Session::GetData($sign) != $requestKey) throw new \ErrorException("Your request is invalid!");
		if($date != date(self::$DateTimeSignFormat)) throw new \ErrorException("Your request is expired!");
		Session::ForgetData($sign);
		return $sign;
    }
}
?>