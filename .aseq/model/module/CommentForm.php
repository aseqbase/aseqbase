<?php
namespace MiMFa\Module;

use MiMFa\Library\Contact;
use MiMFa\Library\Html;
use MiMFa\Library\Convert;
use MiMFa\Library\User;

module("Form");
class CommentForm extends Form
{
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
	public $MessageType = "texts";
	public $AttachLabel = "Attach";
	public $AttachType = "url";
	public $NamePlaceHolder = "Your Name to display";
	public $ContactPlaceHolder = "Your Email address (will not display)";
	public $SubjectPlaceHolder = "The subject of your message";
	public $MessagePlaceHolder = "Leave your message...";
	public $AttachPlaceHolder = "Attached link";
	public $IncompleteWarning = "Please fill all fields correctly!";
	public $SuccessHandler = 'Thank you very much, Your message received successfully!';
	public $ErrorHandler = 'There a problem is occured in processing your message!';
	public $SigningLabel = "Log in or create an account to leave your message";
	public $BlockTimeout = 60000;
	public $ResponseView = null;
	public $ReplyId = null;
	public $Relation = null;
	public $DefaultAccess = 0;
	public $DefaultStatus = 1;
    public $Printable = false;

	public function __construct()
	{
		parent::__construct();
		$this->Access = \_::$Config->WriteCommentAccess;
		$this->DefaultStatus = \_::$Back->User->Access(\_::$Config->AdminAccess) ? 1 : \_::$Config->DefaultCommentStatus;
		$this->Template = "b";
	}

	public function NotifyCommander($commentId, $data, $notification = "Reply to", $subject = null, $message = null)
	{
		try {
			$row = table("Comment")->SelectRow("Contact, Subject", "Id=:Id", [":Id" => $commentId]);
			Contact::SendHtmlEmail(
				\_::$Info->SenderEmail,
				$row["Contact"],
				$subject ?? __($this->MailSubject ?? ("$notification " . getValid($row, "Subject", "Your Comment"))),
				$message ?? [
					$notification => Html::Link(getValid($row, "Subject", "Your Comment"), \Req::$Path),
					"Subject" => Convert::ToText(get($data, "Subject")),
					"Name" => Convert::ToText(getValid($data, "Name", \_::$Back->User ? \_::$Back->User->Name : null)),
					"Contact" => getValid($data, "Contact", \_::$Back->User ? \_::$Back->User->Email : null),
					"Content" => Convert::ToText(get($data, "Content")),
					"Attach" => Convert::ToText(get($data, "Attach"))
				]
			);
		} catch (\Exception $ex) {
		}
	}

	public function Get()
	{
		if (!$this->CheckAccess(access: $this->Access ?? \_::$Config->UserAccess, blocking: false)) {
			$this->Signing = true;
			return $this->GetSigning();
		}
		return parent::Get();
	}
	public function GetFields()
	{
		if (!(\_::$Back->User && \_::$Back->User->Email)) {
			if (isValid($this->NameLabel))
				yield Html::Field(
					key: "Name",
					value: null,
					type: "text",
					title: $this->NameLabel,
					attributes: ["placeholder" => $this->NamePlaceHolder, "required" => "required", "autocomplete" => "Name"]
				);
			if (isValid($this->ContactLabel))
				yield Html::Field(
					key: "Contact",
					value: null,
					type: "email",
					title: $this->ContactLabel,
					attributes: ["placeholder" => $this->ContactPlaceHolder, "required" => "required", "autocomplete" => "Email"]
				);
		}
		if (isValid($this->SubjectLabel))
			yield Html::Field(
				key: "Subject",
				value: null,
				type: "text",
				title: $this->SubjectLabel,
				attributes: ["placeholder" => $this->SubjectPlaceHolder, "required" => "required", "autocomplete" => "Subject"]
			);
		if (isValid($this->MessageLabel))
			yield Html::Field(
				key: "Content",
				value: null,
				type: $this->MessageType,
				title: $this->MessageLabel,
				attributes: ["placeholder" => $this->MessagePlaceHolder, "required" => "required"]
			);
		if (isValid($this->AttachLabel))
			yield Html::Field(
				key: "Attach",
				value: null,
				type: $this->AttachType,
				title: $this->AttachLabel,
				attributes: ["placeholder" => $this->AttachPlaceHolder, "autocomplete" => "Attach"]
			);
		if ($this->ReplyId)
			yield Html::HiddenInput("Reply", $this->ReplyId);
		yield from parent::GetFields();
	}
	public function GetFooter()
	{
		return $this->AllowHeader || (\_::$Back->User && \_::$Back->User->Email) ? "" : parent::GetFooter()
			. Html::LargeSlot(
				$this->GetSigning()
				,
				["class" => "col-lg-12"]
			);
	}

	public function Post()
	{
		if ($this->CheckAccess(access: $this->Access ?? \_::$Config->UserAccess, blocking: true, reaction: true))
			try {
				$received = \Req::ReceivePost();
				if (isValid($received, "Name") || isValid($received, "Content") || isValid($received, "Subject") || isValid($received, "Attach")) {
					$res = null;
					$rid = get($received, "Reply");
					$att = get($received, "Attach");
					if ((\_::$Back->User && \_::$Back->User->Email) || isValid($received, "Contact"))
						$res = table("Comment")->Insert([
							"ReplyId" => $rid,
							"Relation" => $this->Relation,
							"UserId" => \_::$Back->User ? \_::$Back->User->Id : null,
							"Name" => Convert::ToText(getValid($received, "Name", \_::$Back->User ? \_::$Back->User->Name : null)),
							"Contact" => getValid($received, "Contact", \_::$Back->User ? \_::$Back->User->Email : null),
							"Subject" => Convert::ToText(get($received, "Subject")),
							"Content" => Convert::ToText(get($received, "Content")),
							"Attach" => Convert::ToText(isStatic($att) ? $att : Convert::ToJson($att)),
							"Access" => $this->DefaultAccess,
							"Status" => $this->DefaultStatus
						]);

					if ($res) {
						$this->Handler($received);
						if (isValid($rid))
							$this->NotifyCommander($rid, $received, "Reply to");
						$this->Result = true;
						return $this->GetSuccess();
					} else
						return $this->GetError();
				} else
					return $this->GetWarning($this->IncompleteWarning);
			} catch (\Exception $ex) {
				return $this->GetError($ex);
			}
		return $this->GetSigning();
	}

	public function Put()
	{
		if ($this->CheckAccess(access: $this->Access ?? \_::$Config->UserAccess, blocking: true, reaction: true))
			try {
				$received = \Req::ReceivePut();
				if (isValid($received, "Content") || isValid($received, "Subject") || isValid($received, "Attach")) {
					$res = null;
					$cid = get($received, "Id");
					$att = get($received, "Attach");
					if (isValid($cid))
						$res = table("Comment")->Update("`Id`=:Id AND (" . (\_::$Back->User->Access(\_::$Config->AdminAccess) ? "TRUE OR " : "") . "`UserId`=:UserId OR `Contact`=:Contact)", [
							":Id" => $cid,
							":UserId" => \_::$Back->User->Id,
							":Contact" => \_::$Back->User->Email,
							"Subject" => Convert::ToText(get($received, "Subject")),
							"Content" => Convert::ToText(get($received, "Content")),
							"Attach" => Convert::ToText(isStatic($att) ? $att : Convert::ToJson($att)),
							"UpdateTime" => Convert::ToDateTimeString(),
							"Access" => $this->DefaultAccess,
							"Status" => $this->DefaultStatus
						]);
					if ($res) {
						$this->Result = 0;
						return $this->GetSuccess();
					} else
						return $this->GetError();
				} else
					return $this->GetWarning($this->IncompleteWarning);
			} catch (\Exception $ex) {
				return $this->GetError($ex);
			}
		return $this->GetSigning();
	}

	public function Patch()
	{
		$received = \Req::ReceivePatch();
		if ($this->CheckAccess(access: $this->Access ?? \_::$Config->UserAccess, blocking: false, reaction: true))
			if (isValid($received, "Reply")) {
				$this->UnBlock();
				$this->AllowHeader =
					$this->AllowFooter = false;
				$this->ContentClass = "";
				$this->SubjectLabel = null;
				$this->SubmitLabel = $this->ReplyLabel;
				$this->ReplyId = get($received, "Reply");
				$this->Router->Refresh()->Get()->Switch();
				return $this->Handle();
			} elseif (isValid($received, "Status") && \_::$Back->User->Access(\_::$Config->AdminAccess)) {
				$res = null;
				$cid = get($received, "Id");
				if (isValid($cid))
					$res = table("Comment")->Update("`Id`=:Id", [
						":Id" => $cid,
						"Status" => getValid($received, "Status", $this->DefaultStatus)
					]);
				if ($res) {
					$this->Result = 0;
					return $this->GetSuccess();
				} else
					return $this->GetError();
			}
		return $this->GetSigning();
	}

	public function Delete()
	{
		if (auth(\_::$Config->UserAccess))
			try {
				$received = \Req::ReceiveDelete();
				$cid = get($received, "Id");
				if (isValid($cid))
					if (
						isValid($cid) && \_::$Back->User->Id &&
						table("Comment")->Delete("`Id`=:Id AND (" . (\_::$Back->User->Access(\_::$Config->AdminAccess) ? "TRUE OR " : "") . "`UserId`=:UId OR `Contact`=:UE)", [":Id" => $cid, ":UId" => \_::$Back->User->Id, ":UE" => \_::$Back->User->Email])
					) {
						$this->Status = 200;
						return $this->GetWarning("This comment removed successfuly!");
					} else
						return $this->GetError("You have not enough access to remove this comment!");
				return $this->GetError("Could not remove this comment!");
			} catch (\Exception $ex) {
				return $this->GetError($ex);
			}
		return $this->GetSigning();
	}
}
?>