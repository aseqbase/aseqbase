<?php
namespace MiMFa\Library;
require_once "DataBase.php";
require_once "DataTable.php";
require_once "Cryptograph.php";
require_once "Session.php";
require_once "SpecialCrypt.php";
class User extends \Base{
	public static $HandlerPath = "/sign";
	public static $SignHandlerPath = "/sign/sign.php";
	public static $UpHandlerPath = "/sign/up.php";
	public static $InHandlerPath = "/sign/in.php";
	public static $OutHandlerPath = "/sign/out.php";
	public static $RoutePath = "/sign/view.php";
	public static $EditHandlerPath = "/sign/edit.php";
	public static $DashboardHandlerPath = "/sign/dashboard.php";
	public static $RecoverHandlerPath = "/sign/recover.php";
	public static $DefaultImagePath = "/asset/symbol/user.png";

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

	public static $PasswordSecure = true;
	public static $PasswordPattern = "/([\w\W]+){8,64}/";
	public static $PasswordTips = "The password should be more than eigh and less than 64 alphabetic and numeric characters.";

	public static $SeparatorSign = "�";
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


	public $Id = null;
	public $GroupId = null;
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
	
	public DataTable $DataTable;
	public DataTable $GroupDataTable;
	public Session $Session;
	public Cryptograph $Cryptograph;

	public function __construct(DataTable $dataTable, DataTable $groupDataTable, Session $session){
		parent::__construct();
		$this->DataTable = $dataTable;
		$this->GroupDataTable = $groupDataTable;
		$this->Session = $session;
		$this->Cryptograph = new SpecialCrypt();
		if(self::$Active) $this->Refresh();
	}

	public function Load(){
		$this->Refresh();
		return $this->Profile = getValid($this->DataTable->DoSelect("*","`Id`=:Id",[":Id"=>$this->Id]),0);
	}
	public function Refresh(){
		$this->Signature = $this->Session->GetSecure("Signature" );
		$this->Password = $this->Session->GetSecure("Password" );
		$signature = $this->Session->GetData("Signature_".$this->Signature);
		$person = null;
		try{
            if($signature === $this->Signature && isValid($this->Signature) && isValid($this->Password))
                $person = $this->Find($this->Signature, $this->Password, false);
            $this->Session->SetSecure("Signature" , $this->Signature = getValid($person,"Signature" ));
            $this->Session->SetSecure("Id" ,$this->Id = getValid($person,"Id" ));
            $this->Session->SetSecure("GroupId" ,$this->GroupId = getValid($person,"GroupId" ));
            $this->Session->SetSecure("Image" ,$this->Image = getValid($person,"Image" ));
            $this->Session->SetSecure("Name" ,$this->Name = getValid($person,"Name" ));
            $this->Session->SetSecure("Email",$this->Email = getValid($person,"Email"));
            $this->Session->SetSecure("Access" ,$this->Access = is_null($this->GroupId)? null: $this->GroupDataTable->DoSelectValue("`Access`","`Id`=".$this->GroupId));
        }catch(\Exception $ex){ $this->SignOut(); }
		return !is_null($this->Id);
    }

    /**
     * Check if the user has access to the page or not
     * @param int|array|null $acceptableAccess The minimum accessibility for the user, pass null to give the user access
     * @return int|mixed The user accessibility group
     */
	public function Access($acceptableAccess=null){
		if(is_null($acceptableAccess)) return ($this->Access)??\_::$Config->GuestAccess;
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
		if(is_null($access)) $access = \_::$Config->GuestAccess;
		if(is_null($acceptableAccess)) return $access;
		if($acceptableAccess === true || $acceptableAccess === false) return $acceptableAccess;
		if(is_integer($acceptableAccess)) return $acceptableAccess > 0? ($access > 0? $access <= $acceptableAccess:false) : ($access >= $acceptableAccess);
		if(is_array($acceptableAccess) && count($acceptableAccess) > 0)
			if(isset($acceptableAccess["min"]) || isset($acceptableAccess["max"]))
				return (!isset($acceptableAccess["min"]) || $acceptableAccess["min"] <= $access) && (!isset($acceptableAccess["max"]) ||$acceptableAccess["max"] >= $access);
			else return in_array($access, $acceptableAccess);
		return null;
    }
	public static function GetAccessCondition($checkStatus = true, $checkAccess = true){
        $acc = auth();
        return " ".($checkStatus?"(`Status` IS NULL OR `Status` IN ('','1',1)) ":"").
				($checkStatus && $checkAccess?" AND ":"").
				($checkAccess? "`Access`>".\_::$Config->BanAccess."
				AND (`Access`=".\_::$Config->GuestAccess.($acc<=\_::$Config->GuestAccess?"":" OR `Access`>=$acc").')'.
				(\_::$Config->AllowTranslate?("AND (`MetaData` IS NULL OR `MetaData` NOT REGEXP '/\s*([\"\\']?)lang\1\s*\:/i' OR `MetaData` REGEXP '/\s*([\"\\']?)lang\1\s*\:[\s\S]*([\"\\'])".\_::$Back->Translate->Language."\2/i')"):''):"");
    }

	public function Find($signature = null, $password = null, $hashPassword = true){
		if(!self::$Active) return [];
		$signature = $signature??$this->Signature??$this->Session->GetSecure("Signature" )??$this->TemporarySignature;
		if(!isValid($signature)){
            //$this->SignOut($signature);
            return [];
        }
		if(isValid($password)) {
			if($hashPassword) $password = $this->EncryptPassword($password);
			$person = $this->DataTable->DoSelect(
						"`Signature` , `Id` , `GroupId` , `Image` , `Name` , `Email` , `Password` , `Status`",
						"(`Id`=:Id OR `Signature`=:Signature OR `Email`=:Email OR (`Contact` IS NOT NULL AND `Contact`=:Contact)) AND `Password`=:Password",
						[":Id"=>$signature,":Signature"=>$signature,":Email"=>$signature,":Contact"=>$signature,":Password"=> $password]
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
			$person = $this->DataTable->DoSelect(
						"`Signature` , `Id` , `GroupId` , `Image` , `Name` , `Email` , `Password` , `Status`",
						"(`Id`=:Id OR `Signature`=:Signature OR `Email`=:Email OR (`Contact` IS NOT NULL AND `Contact`=:Contact))",
						[":Id"=>$signature,":Signature"=>$signature,":Email"=>$signature,":Contact"=>$signature]
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
		$this->TemporarySignature = getValid($person,"Signature" );
		$this->TemporaryImage = getValid($person,"Image" );
		$this->TemporaryName = getValid($person,"Name" );
		$this->TemporaryEmail = getValid($person,"Email");
		return $person;
	}
	public function Get($signature = null, $password = null){
        if(is_null($id =$this->Id) || !is_null($signature)) $id = getValid($this->Find($signature, $password), "Id" );
		return is_null($id)?null:getValid($this->DataTable->DoSelect("*","`Id`=:Id",[":Id"=>$id]),0);
    }
	public function Set($fieldsDictionary, $signature = null, $password = null){
        $id = $this->Id;
        $email = $this->Email;
		if(!is_null($signature) || is_null($id) || is_null($email)) {
			$person = $this->Find($signature, $password);
            $id = getValid($person,"Id" );
            $email = getValid($person,"Email");
        }
		if(is_null($id)) return null;
		if(getValid($fieldsDictionary, "Email", $email)!=$email)
			$fieldsDictionary["Status" ] = self::$InitialStatus;
		return $this->DataTable->DoUpdate("`Id`='$id'",$fieldsDictionary);
    }
	public function GetValue($key, $signature = null, $password = null){
        if(is_null($id = $this->Id) || !is_null($signature)) $id = getValid($this->Find($signature, $password), "Id" );
		return is_null($id)?null:$this->DataTable->DoSelectValue($key,"`Id`=$id");
    }
	public function SetValue($key, $value, $signature = null, $password = null){
        if(is_null($id =$this->Id) || !is_null($signature)) $id = getValid($this->Find($signature, $password), "Id" );
		return is_null($id)?null:$this->DataTable->DoUpdate("`Id`=$id",[$key => $value]);
    }

	public function GetGroup($signature = null, $password = null){
        if(is_null($id =$this->GroupId) || !is_null($signature)) $id = getValid($this->Find($signature, $password), "GroupId" );
		return is_null($id)?null:getValid($this->GroupDataTable->DoSelect("*","`Id`=:Id",[":Id"=>$id]),0);
    }
	public function SetGroup($fieldsDictionary, $signature = null, $password = null){
        $id = $this->GroupId;
		if(!is_null($signature) || is_null($id)) {
			$person = $this->Find($signature, $password);
            $id = getValid($person,"GroupId" );
        }
		if(is_null($id)) return null;
		return $this->GroupDataTable->DoUpdate("`Id`='$id'",$fieldsDictionary);
    }

	public function SignUp($signature, $password, $email = null, $name = null, $firstName = null, $lastName = null, $phone = null, $groupId = null, $status = null){
		$password = $this->CheckPassword($password);
		$this->TemporaryImage = null;
		return $this->DataTable->DoInsert(null,
			[
				":Signature"=>$this->TemporarySignature = $signature??$email,
				":Email"=>$this->TemporaryEmail = $email,
				":Password"=> $password,
				":Name"=> $this->TemporaryName = $name?? trim($firstName." ".$lastName),
				":FirstName"=> $firstName,
				":LastName"=> $lastName,
				":Contact"=> $phone,
				":GroupId"=> $groupId??$this->GroupDataTable->DoSelectValue("`Id`", "Access=". \_::$Config->UserAccess)??100,
				":Status"=> $status
			]);
	}
	public function SignIn($signature, $password){
		if(!isValid($password)) return false;
		$person = $this->Find($signature, $password);
		$status = getValid($person,"Status" , self::$InitialStatus);
		if($status === false || ((int)$status) < self::$ActiveStatus)
			throw new \ErrorException(
				"This account is not active yet!<br>".
				"<a href='".self::$ActiveHandlerPath."?signature=$signature'>".__("Try to send the activation email again!")."</a>"
			);
		$this->Session->SetData("Signature_".($this->Signature = getValid($person,"Signature" )), $this->Signature = getValid($person,"Signature" ));
		$this->Session->SetSecure("Signature" , $this->Signature);
		$this->Session->SetSecure("Password" , $this->Password = getValid($person,"Password" ));
		$this->Session->SetSecure("Id" , $this->Id = getValid($person,"Id" ));
		$this->Session->SetSecure("GroupId" , $this->GroupId = getValid($person,"GroupId" ));
		$this->Session->SetSecure("Image" , $this->Image = getValid($person,"Image" ));
		$this->Session->SetSecure("Name" , $this->Name = getValid($person,"Name" ));
		$this->Session->SetSecure("Email", $this->Email = getValid($person,"Email"));
		$this->Session->SetSecure("Access" , $this->Access = $this->GroupDataTable->DoSelectValue("`Access`","`Id`=".$this->GroupId));
		return true;
	}
	public function SignInOrSignUp($signature, $password, $email = null){
		return $this->SignIn($signature??$email, $password)??
			$this->SignUp($signature??$email, $password, $email);
	}
	public function SignOut($signature = null){
		if($signature === null || $signature === $this->Signature){
			$this->Session->ForgetData("Signature" );
            $this->Session->ForgetSecure("Password" );
            $this->Session->ForgetSecure("Signature" );
            $this->Session->ForgetSecure("Id" );
            $this->Session->ForgetSecure("GroupId" );
            $this->Session->ForgetSecure("Image" );
            $this->Session->ForgetSecure("Name" );
            $this->Session->ForgetSecure("Email");
            $this->Session->ForgetSecure("Access" );
            $this->Load();
        } else $this->Session->ForgetData($signature);
		return !self::Access(\_::$Config->UserAccess);
	}

	public function ResetPassword($signature = null, $password = null){
		return $this->SetValue("Password" , $this->CheckPassword($password), $signature);
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
		if(\Req::Receive(self::$ActiveRequestKey)) return $this->ReceiveActivationLink();
		if(\Req::Receive(self::$RecoveryRequestKey)) return $this->ReceiveRecoveryLink();
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
		return $this->SendEmail($emailFrom??\_::$Info->SenderEmail, $emailTo??$this->TemporaryEmail, $subject??self::$ActiveEmailSubject, $content??self::$ActiveEmailContent, $linkAnchor??self::$ActiveLinkAnchor, self::$ActiveHandlerPath, self::$ActiveRequestKey);
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
		return $this->DataTable->DoUpdate(
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
		return $this->SendEmail($emailFrom??\_::$Info->SenderEmail, $emailTo??$this->TemporaryEmail, $subject??self::$RecoverEmailSubject, $content??self::$RecoverEmailContent, $linkAnchor??self::$RecoverLinkAnchor, self::$RecoverHandlerPath, self::$RecoveryRequestKey);
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
		$newPass = \Req::Receive("Password" );
		if($newPass != \Req::Receive("PasswordConfirmation", $newPass)) throw new \ErrorException("New password and its confirmation does not match!");
		else if(isValid($newPass)){
			$this->CheckPassword($newPass);
            $sign = $this->ReceiveLink(self::$RecoveryRequestKey);
            if(empty($sign)) return null;
            return self::ResetPassword($sign, $newPass);
        }
		return false;
    }


	public function SendEmail($emailFrom, $emailTo, $subject, $content, $linkAnchor="Click Here", $handlerPath = null, $requestKey="Key" ){
		$person = getValid($this->DataTable->DoSelect("Signature" ,"`Email`=:Email",[":Email"=> $emailTo]),0);
		if(is_null($person)) throw new \ErrorException("Unfortunately the email address is incorrect!");
		$sign = getValid($person,"Signature" );
		$this->Session->SetData($sign,$requestKey);
		$path = \Req::$Host.($handlerPath??self::$HandlerPath)."?".$requestKey."=".urlencode($this->Cryptograph->Encrypt($sign.self::$SeparatorSign.date(self::$DateTimeSignFormat),\_::$Config->SecretKey, true));
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
		$rp = \Req::Receive( $requestKey);
		if(is_null($rp)) throw new \ErrorException("It is not a valid request!");
		$sign = $this->Cryptograph->Decrypt($rp,\_::$Config->SecretKey, true);
		list($sign, $date) = explode(self::$SeparatorSign, $sign);
		if($this->Session->GetData($sign) != $requestKey) throw new \ErrorException("Your request is invalid or used before!");
		if($date != date(self::$DateTimeSignFormat)) throw new \ErrorException("Your request is expired!");
        $person = self::Find($sign);
		return $person;
    }
	public function ReceiveLink($requestKey){
		$rp = \Req::Receive($requestKey);
		if(is_null($rp)) return false;
		$sign = $this->Cryptograph->Decrypt($rp,\_::$Config->SecretKey, true);
		list($sign, $date) = explode(self::$SeparatorSign, $sign);
		if($this->Session->GetData($sign) != $requestKey) throw new \ErrorException("Your request is invalid or used before!");
		if($date != date(self::$DateTimeSignFormat)) throw new \ErrorException("Your request is expired!");
		$this->Session->ForgetData($sign);
        $person = self::Find($sign);
		return $person["Signature" ];
    }
}
?>