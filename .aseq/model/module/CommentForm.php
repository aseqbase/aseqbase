<?php
namespace MiMFa\Module;

use MiMFa\Library\Contact;
use MiMFa\Library\Struct;
use MiMFa\Library\Convert;


module("Form");
class CommentForm extends Form
{
	public $Action = null;
	public $Method = "post";
	public $Title = "Leave a Comment";
	public $Description = "Leave your Comment about this 'item' here!";
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
	public $ErrorHandler = 'Something went wrong in processing your message!';
	public $SigningLabel = "Log in or create an account to leave your message";
	public $BlockTimeout = 60000;
	public $ResponseView = null;
	public $RootId = null;
	public $Relation = null;
	public $DefaultAccess = 0;
	public $DefaultStatus = 1;
    public $Printable = false;

	public function __construct($relation=null, $access = null)
	{
		parent::__construct();
		$this->Relation = $relation;
		$this->Access = $access ?? \_::$Config->WriteCommentAccess;
		$this->DefaultStatus = \_::$User->GetAccess(\_::$User->AdminAccess) ? 1 : \_::$Config->DefaultCommentStatus;
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
					$notification => Struct::Link(getValid($row, "Subject", "Your Comment"), \_::$Address->Path),
					"Subject" => Convert::ToText(get($data, "Subject")),
					"Name" => Convert::ToText(getValid($data, "Name", \_::$User ? \_::$User->Name : null)),
					"Contact" => getValid($data, "Contact", \_::$User ? \_::$User->Email : null),
					"Content" => Convert::ToText(get($data, "Content")),
					"Attach" => Convert::ToText(get($data, "Attach"))
				]
			);
		} catch (\Exception $ex) {
		}
	}

	public function Get()
	{
		if (!$this->CheckAccess($this->Access ?? \_::$User->UserAccess, false)) {
			$this->Signing = true;
			return $this->GetSigning();
		}
		return parent::Get();
	}
	public function GetFields()
	{
		if($this->Relation) yield Struct::HiddenInput("Relation", $this->Relation);
		if (!\_::$User->Email) {
			if (isValid($this->NameLabel))
				yield Struct::Field(
					key: "Name",
					value: null,
					type: "text",
					title: $this->NameLabel,
					attributes: ["placeholder" => $this->NamePlaceHolder, "required" => "required", "autocomplete" => "Name"]
				);
			if (isValid($this->ContactLabel))
				yield Struct::Field(
					key: "Contact",
					value: null,
					type: "email",
					title: $this->ContactLabel,
					attributes: ["placeholder" => $this->ContactPlaceHolder, "required" => "required", "autocomplete" => "Email"]
				);
		}
		if (isValid($this->SubjectLabel))
			yield Struct::Field(
				key: "Subject",
				value: null,
				type: "text",
				title: $this->SubjectLabel,
				attributes: ["placeholder" => $this->SubjectPlaceHolder, "required" => "required", "autocomplete" => "Subject"]
			);
		if (isValid($this->MessageLabel))
			yield Struct::Field(
				key: "Content",
				value: null,
				type: $this->MessageType,
				title: $this->MessageLabel,
				attributes: ["placeholder" => $this->MessagePlaceHolder, "required" => "required"]
			);
		if (isValid($this->AttachLabel))
			yield Struct::Field(
				key: "Attach",
				value: null,
				type: $this->AttachType,
				title: $this->AttachLabel,
				attributes: ["placeholder" => $this->AttachPlaceHolder, "autocomplete" => "Attach"]
			);
		if ($this->RootId)
			yield Struct::HiddenInput("Root", $this->RootId);
		yield from parent::GetFields();
	}
	public function GetFooter()
	{
		return $this->AllowHeader || (\_::$User && \_::$User->Email) ? "" : parent::GetFooter()
			. Struct::LargeSlot(
				$this->GetSigning()
				,
				["class" => "col-lg-12"]
			);
	}

	public function Post()
	{
		if ($this->CheckAccess(access: $this->Access ?? \_::$User->UserAccess, blocking: true, reaction: true))
			try {
				$received = receivePost();
				if (isValid($received, "Name") || isValid($received, "Content") || isValid($received, "Subject") || isValid($received, "Attach")) {
					$res = null;
					$rid = get($received, "Root");
					$att = get($received, "Attach");
					if ((\_::$User && \_::$User->Email) || isValid($received, "Contact"))
						$res = table("Comment")->Insert([
							"RootId" => $rid,
							"Relation" => $this->Relation,
							"UserId" => \_::$User ? \_::$User->Id : null,
							"Name" => Convert::ToText(getValid($received, "Name", \_::$User ? \_::$User->Name : null)),
							"Contact" => getValid($received, "Contact", \_::$User ? \_::$User->Email : null),
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
		if ($this->CheckAccess(access: $this->Access ?? \_::$User->UserAccess, blocking: true, reaction: true))
			try {
				$received = receivePut();
				if (isValid($received, "Content") || isValid($received, "Subject") || isValid($received, "Attach")) {
					$res = null;
					$cid = get($received, "Id");
					$att = get($received, "Attach");
					if (isValid($cid))
						$res = table("Comment")->Update("`Id`=:Id AND (" . (\_::$User->GetAccess(\_::$User->AdminAccess) ? "TRUE OR " : "") . "`UserId`=:UserId OR `Contact`=:Contact)", [
							":Id" => $cid,
							":UserId" => \_::$User->Id,
							":Contact" => \_::$User->Email,
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
		$received = receivePatch();
		if ($this->CheckAccess(access: $this->Access ?? \_::$User->UserAccess, blocking: false, reaction: true))
			if (isValid($received, "Root")) {
				popTimer();
				$this->AllowHeader =
					$this->AllowFooter = false;
				$this->ContentClass = "";
				$this->SubjectLabel = null;
				$this->SubmitLabel = $this->ReplyLabel;
				$this->RootId = get($received, "Root");
				$this->Router->Initial()->Get()->Switch();
				return $this->Handle();
			} elseif (isValid($received, "Status") && \_::$User->GetAccess(\_::$User->AdminAccess)) {
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
		if (\_::$User->GetAccess(\_::$User->UserAccess))
			try {
				$received = receiveDelete();
				$cid = get($received, "Id");
				if (isValid($cid))
					if (
						isValid($cid) && \_::$User->Id &&
						table("Comment")->Delete("`Id`=:Id AND (" . (\_::$User->GetAccess(\_::$User->AdminAccess) ? "TRUE OR " : "") . "`UserId`=:UId OR `Contact`=:UE)", [":Id" => $cid, ":UId" => \_::$User->Id, ":UE" => \_::$User->Email])
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