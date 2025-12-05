<?php
namespace MiMFa\Module;

use MiMFa\Library\Contact;
use MiMFa\Library\Struct;
use MiMFa\Library\Convert;
use MiMFa\Library\Local;

module("Form");
class MessageForm extends Form
{
	public $Action = null;
	public $Access = 1;
	public $From = null;
	public $To = null;
	public $Title = "Leave a Message";
	public $Description = "Leave your Message about this 'item' here!";
	public $Image = "Message";
	public $SubmitLabel = "<i class='fa fa-paper-plane'></i>";
	public $ReplyLabel = "<i class='fa fa-paper-plane'></i>";
	public $ResetLabel = null;
	public $BackLabel = null;
	public $ContentLabel = "Message";
	public $ContentType = "texts";
	public $IncompleteWarning = "Please fill all fields correctly!";
	public $SuccessHandler = 'Thank you very much, Your message received successfully!';
	public $ErrorHandler = 'Something went wrong in processing your message!';
	public $SigningLabel = "Log in or create an account to leave your message";
	public $BlockTimeout = 10000;
	public $ResponseView = null;
	public $RootId = null;
	public $Relation = null;
	public $DefaultAccess = 0;
	public $DefaultStatus = 1;
	public $ContentClass = "";
	public $AllowHeader = false;
	public $AllowFooter = false;
	public $Printable = false;

	public function __construct($relation = null, $access = null)
	{
		parent::__construct();
		$this->Relation = $relation;
		$this->Access = $access ?? \_::$Config->WriteCommentAccess;
		$this->DefaultStatus = 1;
		$this->Template = null;
	}

	public function NotifyCommander($messageId, $data, $notification = "Reply to", $subject = null, $message = null)
	{
		try {
			$row = table("Message")->SelectRow("`To`, Subject", "Id=:Id", [":Id" => $messageId]);
			if (isEmail($row["To"]))
				Contact::SendHtmlEmail(
					\_::$Info->SenderEmail,
					$row["To"],
					$subject ?? __($this->MailSubject ?? ("$notification " . getValid($row, "Subject", "Your Message"))),
					$message ?? [
						$notification => Struct::Link(getValid($row, "Subject", "Your Message"), \_::$User->Path),
						"Name" => Convert::ToText(getValid($data, "Name", \_::$User->Name)),
						"From" => getValid($data, "From", \_::$User->Email),
						"Content" => Convert::ToText(get($data, "Content")),
						"Attach" => Convert::ToText(get($data, "Attach"))
					]
				);
		} catch (\Exception $ex) {
		}
	}

	public function GetStyle()
	{
		return parent::GetStyle() . Struct::Style("
		.{$this->Name} {
			padding: 0px;
			margin: 0px;
		}
		.{$this->Name} .content {
			padding: 0px;
		    background-color: transparent;
		}
		.{$this->Name} .form {
			padding: 0px;
		}
		.{$this->Name} .form .group {
			padding: 0px;
		}");
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
		if ($this->Relation)
			yield Struct::HiddenInput("Relation", $this->Relation);
		if ($this->From)
			yield Struct::HiddenInput("From", $this->From);
		if ($this->To)
			yield Struct::HiddenInput("To", $this->To);
		if (isValid($this->ContentLabel))
			yield Struct::Field(
				key: "Content",
				value: null,
				type: $this->ContentType,
				title: false,
				attributes: ["placeholder" => $this->ContentLabel, "required" => "required"]
			);
		if (isValid($this->AttachLabel))
			yield Struct::Field(
				key: "Attach",
				value: null,
				type: $this->AttachType,
				title: false,
				attributes: ["placeholder" => $this->AttachLabel, "autocomplete" => "Attach"]
			);
		if ($this->RootId)
			yield Struct::HiddenInput("Root", $this->RootId);
		yield from parent::GetFields();
	}
	public function GetFooter()
	{
		return \_::$User->Email ? "" : parent::GetFooter()
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
				$content = get($received, "Content");
				$att = get($received, "Attach");
				if (!$att) {
						$att = receiveFile("Attach");
						if ($att) {
							if (Local::IsFileObject($att))
								$att = Local::GetUrl(Local::Store($att));
							elseif (is_array($att)) {
								$att = [];
								foreach ($att as $file) {
									if (Local::IsFileObject($file))
										$att[] = Local::GetUrl(Local::Store($file));
								}
							}
						}
					}
				if ($content || $att) {
					$res = null;
					$rid = get($received, "Root");
					if (\_::$User->Id)
						$res = table("Message")->Insert([
							"RootId" => $rid,
							"Relation" => $this->Relation,
							"UserId" => \_::$User->Id,
							"From" => get($received, "From"),
							"To" => get($received, "To"),
							"Name" => \_::$User->Name,
							"Content" => Convert::ToText($content),
							"Attach" => isStatic($att) ? $att ?? "" : Convert::ToJson($att),
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
				$content = get($received, "Content");
				$att = get($received, "Attach");
				if ($content || $att) {
					$res = null;
					$cid = get($received, "Id");
					if (isValid($cid))
						$res = table("Message")->Update("`Id`=:Id AND (" . (\_::$User->HasAccess(\_::$User->AdminAccess) ? "TRUE OR " : "") . "`UserId`=:UserId)", [
							":Id" => $cid,
							":UserId" => \_::$User->Id,
							"Name" => \_::$User->Name,
							"Content" => Convert::ToText($content),
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
		if ($this->CheckAccess(access: $this->Access ?? \_::$User->UserAccess, blocking: false, reaction: true)){
			$rootId = get($received, "Root");
			if (isValid($rootId)) {
				popTimer();
				$this->AllowHeader =
					$this->AllowFooter = false;
				$this->ContentClass = "";
				$this->SubjectLabel = null;
				$this->SubmitLabel = $this->ReplyLabel;
				$this->Relation = table("Message")->GetValue($rootId, "Relation");
				$this->RootId = $rootId;
				$this->Router->Initial()->Get()->Switch();
				return $this->Handle();
			} elseif (isValid($received, "Status") && \_::$User->HasAccess(\_::$User->AdminAccess)) {
				$res = null;
				$cid = get($received, "Id");
				if (isValid($cid))
					$res = table("Message")->Update("`Id`=:Id", [
						":Id" => $cid,
						"Status" => getValid($received, "Status", $this->DefaultStatus)
					]);
				if ($res) {
					$this->Result = 0;
					return $this->GetSuccess();
				} else
					return $this->GetError();
			}
		}
		return $this->GetSigning();
	}

	public function Delete()
	{
		if (\_::$User->HasAccess(\_::$User->UserAccess))
			try {
				$received = receiveDelete();
				$cid = get($received, "Id");
				if (isValid($cid))
					if (
						isValid($cid) && \_::$User->Id &&
						table("Message")->Delete("`Id`=:Id AND (" . (\_::$User->HasAccess(\_::$User->AdminAccess) ? "TRUE OR " : "") . "`UserId`=:UId)", [":Id" => $cid, ":UId" => \_::$User->Id])
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