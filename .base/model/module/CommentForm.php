<?php
namespace MiMFa\Module;

use MiMFa\Library\Contact;
use MiMFa\Library\HTML;
use MiMFa\Library\Convert;
use MiMFa\Library\DataBase;
use MiMFa\Library\User;
MODULE("Form");
class CommentForm extends Form
{
	public $Capturable = true;
	public $Access = 0;
	public $Action = null;
	public $Method = "post";
	public $Title = "Leave a Comment";
	public $Description = "Leave your Comment about this post here!";
	public $Image = "comment";
	public $SubmitLabel = "Submit";
	public $ReplyLabel = "Reply";
	public $BackLabel = null;
	public $NameLabel = "Name";
	public $ContactLabel = "Email";
	public $SubjectLabel = "Subject";
	public $MessageLabel = "Message";
	public $AttachLabel = "Attach";
	public $AttachType = "url";
	public $MultipleAttach = false;
	public $NamePlaceHolder = "Your Name to display";
	public $ContactPlaceHolder = "Your Email address (will not display)";
	public $SubjectPlaceHolder = "The subject of your message";
	public $MessagePlaceHolder = "Leave your message...";
	public $AttachPlaceHolder = "Attached link";
	public $SignInOrUpLabel = "Log in or create an account to leave your message";
	public $IncompleteWarning = "Please fill all fields correctly!";
	public $SuccessHandler = 'Thank you very much, Your message received successfully!';
	public $ErrorHandler = 'There a problem is occured in processing your message!';
	public $SignUpIfNotRegistered = false;
	public $BlockTimeout = 60000;
	public $ResponseView = null;
	public $ReplyID = null;
	public $Relation = null;
	public $DefaultAccess = 0;
	public $DefaultStatus = 1;

	public function __construct()
	{
		parent::__construct();
		$this->Access = \_::$CONFIG->WriteCommentAccess;
		$this->DefaultStatus = \_::$CONFIG->DefaultCommentStatus;
		$this->Template = "h";
	}

	public function Get()
	{
		if (!getAccess($this->Access ?? \_::$CONFIG->UserAccess)) {
			return $this->GetHeader();
		} else
			return parent::Get();
	}
	public function GetHeader()
	{
		if (getAccess($this->Access ?? \_::$CONFIG->UserAccess))
			return parent::GetHeader();
		else
			return HTML::Link($this->SignInOrUpLabel, User::$InHandlerPath);
	}
	public function GetFields()
	{
		if (!(\_::$INFO->User && \_::$INFO->User->Email)){
			if (isValid($this->NameLabel))
				yield HTML::LargeSlot(
					HTML::Label($this->NameLabel, "Name") .
					HTML::ValueInput("Name", null,
						["placeholder" => $this->NamePlaceHolder, "autocomplete" => "name", "required"=>"required"]
					),
					["class" => "field col"]
				);
			if (isValid($this->ContactLabel))
				yield HTML::LargeSlot(
					HTML::Label($this->ContactLabel, "Contact") .
					HTML::EmailInput("Contact",
						["placeholder" => $this->ContactPlaceHolder, "autocomplete" => "email", "required"=>"required"]
					),
					["class" => "field col"]
				);
		}
		if (isValid($this->SubjectLabel))
			yield HTML::LargeSlot(
				HTML::Label($this->SubjectLabel, "Subject") .
				HTML::ValueInput("Subject",
					["placeholder" => $this->SubjectPlaceHolder, "autocomplete" => "subject"]
				),
				["class" => "field col"]
			);
		if (isValid($this->MessageLabel))
			yield HTML::LargeSlot(
				HTML::Label($this->MessageLabel, "Message") .
				(
					isValid($this->SubjectLabel)
					?HTML::ContentInput("Content", null, ["placeholder" => $this->MessagePlaceHolder, "required"=>"required"])
					:HTML::TextInput("Content", null, ["placeholder" => $this->MessagePlaceHolder, "required"=>"required"])
				),
				["class" => "field col"]
			);
		if (isValid($this->AttachLabel))
			yield HTML::LargeSlot(
				HTML::Label($this->AttachLabel, "Attach") .
				(
					$this->MultipleAttach
					? HTML::CollectionInput("Attach", null, ["type"=>$this->AttachType, "placeholder" => $this->AttachPlaceHolder, "autocomplete" => "attach"])
					: HTML::Input("Attach", null, $this->AttachType, ["placeholder" => $this->AttachPlaceHolder, "autocomplete" => "attach"])
				),
				["class" => "field col"]
			);
		yield from parent::GetFields();
	}
	public function GetFooter()
	{
		return $this->AllowHeader || (\_::$INFO->User && \_::$INFO->User->Email) ? "" : parent::GetFooter()
			. HTML::LargeSlot(
				HTML::Link($this->SignInOrUpLabel, User::$InHandlerPath)
				,
				["class" => "col-lg-12"]
			);
	}

	public function Handler()
	{
		$_req = $_REQUEST;
		switch (strtolower($this->Method)) {
			case "get":
				$_req = $_GET;
				break;
			case "post":
				$_req = $_POST;
				break;
		}
		if (getAccess($this->Access ?? \_::$CONFIG->UserAccess))
			try {
				if (isValid($_req, "Name") || isValid($_req, "Content") || isValid($_req, "Subject") || isValid($_req, "Attach")) {
					$res = null;
					$cid = getValid($_req, "ID");
					$rid = getValid($_req, "Reply");
					$uid = DataBase::DoSelectValue(\_::$CONFIG->DataBasePrefix . "Comment", "UserID", "ID=:ID", [":ID"=>$cid]);
					if(isValid($cid) && $uid == \_::$INFO->User->ID)
						$res = DataBase::DoUpdate(\_::$CONFIG->DataBasePrefix . "Comment", "`ID`=:ID", [
							":ID"=>$cid,
							"Subject" => getValid($_req, "Subject"),
							"Content" => getValid($_req, "Content"),
							"Attach" => Convert::ToString(getValid($_req, "Attach")),
							"Access" => $this->DefaultAccess,
							"Status" => $this->DefaultStatus
						]);
					elseif((\_::$INFO->User && \_::$INFO->User->Email) || isValid($_req, "Contact"))
						$res = DataBase::DoInsert(\_::$CONFIG->DataBasePrefix . "Comment", null, [
							"ReplyID" => $rid,
							"Relation" => $this->Relation,
							"UserID" => \_::$INFO->User ? \_::$INFO->User->ID : null,
							"Name" => getValid($_req, "Name", \_::$INFO->User ? \_::$INFO->User->Name : null),
							"Contact" => getValid($_req, "Contact", \_::$INFO->User ? \_::$INFO->User->Email : null),
							"Subject" => getValid($_req, "Subject"),
							"Content" => getValid($_req, "Content"),
							"Attach" => Convert::ToString(getValid($_req, "Attach")),
							"Access" => $this->DefaultAccess,
							"Status" => $this->DefaultStatus
						]);

					if ($res) {
						if(isValid($rid)) try{
							$row = DataBase::DoSelectRow(\_::$CONFIG->DataBasePrefix . "Comment", "Contact, Subject", "ID=:ID", [":ID"=>$rid]);
							Contact::SendHTMLEmail(\_::$CONFIG->SenderEmail,$row["Contact"], __($this->MailSubject??("Reply to ".getValid($row,"Subject", "Your Comment"))), 
							Convert::ToString([
								"Reply To" => HTML::Link(getValid($row,"Subject", "Your Comment"), \_::$PATH),
								"Subject" => getValid($_req, "Subject"),
								"Name" => getValid($_req, "Name", \_::$INFO->User ? \_::$INFO->User->Name : null),
								"Contact" => getValid($_req, "Contact", \_::$INFO->User ? \_::$INFO->User->Email : null),
								"Content" => getValid($_req, "Content"),
								"Attach" => Convert::ToString(getValid($_req, "Attach"))
						]));
						}catch(\Exception $ex){}
						return $this->GetSuccess(Convert::ToString($this->SuccessHandler));
					}else return $this->GetError(Convert::ToString($this->ErrorHandler));
				} elseif(!RECEIVE("Submit", $_req) && isValid($_req, "Reply")){
						$this->UnBlock();
						$this->Set_Defaults();
						$this->Result = 0;
						$this->AllowHeader = 
						$this->AllowFooter = false;
						$this->ContentClass = "";
						$this->SubjectLabel = null;
						$this->SubmitLabel = $this->ReplyLabel;
						$this->ReplyID = getValid($_req, "Reply");
						return $this->Capture();
				}
				else return $this->GetWarning($this->IncompleteWarning);
			} catch (\Exception $ex) {
				return $this->GetError($ex);
			} else return HTML::Link($this->SignInOrUpLabel, User::$InHandlerPath);
	}
}
?>