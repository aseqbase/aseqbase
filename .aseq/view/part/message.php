<?php
use MiMFa\Library\Convert;
$data = $data ?? [];
module("Form");
(new MiMFa\Library\Router())
	->Post(function () use ($data) {
		$form = new MiMFa\Module\Form();
		$received = \Req::ReceivePost();
		if (!get($received, "Message"))
			\Res::Render($form->GetError("Your message could not be empty!"));
		else {
			$form->MailSubject = \Req::$Domain . ": Message from '" . (get($received, "Name") ?? get(\_::$Back->User, "Name")) . "'";
			$form->ReceiverEmail = \_::$Info->ReceiverEmail;
			$form->SenderEmail = get($received, "Email") ?? get(\_::$Back->User, "Email");
			table("Message")->Insert([
				"UserId" => \_::$Back->User ? \_::$Back->User->Id : null,
				"Type" => \Req::$Url,
				"Name" => Convert::ToText(getValid($received, "Name", \_::$Back->User ? \_::$Back->User->Name : null)),
				"From" => $form->SenderEmail,
				"To" => $form->ReceiverEmail,
				"Subject" => Convert::ToText(get($received, "Subject")),
				"Content" => Convert::ToText(get($received, "Message")),
				"Access" => \_::$Config->AdminAccess,
				"Status" => -1
			]);
			swap($form, $data);
			$form->End();
		}
	})
	->Get(function () use ($data) {
		$form = new MiMFa\Module\Form(method:"POST");
		$form->Title = get($data, "Title") ?? "Leave a message to Us!";
		$form->Image = get($data, "Image") ?? "envelope";
		$form->Description = get($data, "Description");
		$form->Content = get($data, "Content");
		$form->Template = get($data, "Template") ?? "t";
		$form->BlockTimeout = get($data, "BlockTimeout") ?? 12;
		$form->BackLabel = get($data, "BackLabel") ?? null;
		$form->BackPath = get($data, "BackPath") ?? null;
		$form->ContentClass = "col-lg-8";
		module("Field");
		$name = get(\_::$Back->User, "Name") ?? \Req::ReceiveGet("Name");
		$email = get(\_::$Back->User, "Email") ?? \Req::ReceiveGet("Email");
		$msg = \Req::ReceiveGet("Message");
		$form->Children = [new MiMFa\Module\Field("text", "Subject", $msg, required: true)];
		if (!\_::$Back->User->Email) {
			$form->Children[] = new MiMFa\Module\Field("text", "Name", $name, required: true, lock: !isEmpty($name));
			$form->Children[] = new MiMFa\Module\Field("email", "Email", $email, required: true, lock: !isEmpty($email));
		}
		$form->Children[] = new MiMFa\Module\Field("texts", "Message", $msg, required: true);
		swap($form, $data);
		$form->Render();
	})->Handle();
?>