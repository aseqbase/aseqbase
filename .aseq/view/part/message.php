<?php
use MiMFa\Library\Convert;
$data = $data ?? [];
module("Form");
(new Router())
	->Post(function () use ($data) {
		$form = new MiMFa\Module\Form();
		$received = receivePost();
		if (!get($received, "Message"))
			render($form->GetError("Your message could not be empty!"));
		else {
			$form->MailSubject = \_::$Address->Domain . ": Message from '" . (get($received, "Name") ?? get(\_::$User, "Name")) . "'";
			$form->ReceiverEmail = \_::$Info->ReceiverEmail;
			$form->SenderEmail = get($received, "Email") ?? get(\_::$User, "Email");
			table("Message")->Insert([
				"UserId" => \_::$User ? \_::$User->Id : null,
				"Type" => \_::$Address->Url,
				"Name" => Convert::ToText(getValid($received, "Name", \_::$User ? \_::$User->Name : null)),
				"From" => $form->SenderEmail,
				"To" => $form->ReceiverEmail,
				"Subject" => Convert::ToText(get($received, "Subject")),
				"Content" => Convert::ToText(get($received, "Message")),
				"Access" => \_::$User->AdminAccess,
				"Status" => -1
			]);
			swap($form, $data);
			$form->Render();
		}
	})
	->Get(function () use ($data) {
		$form = new MiMFa\Module\Form(method:"POST");
		$form->Title = get($data, "Title") ?? "Leave a message to Us!";
		$form->Image = get($data, "Image") ?? "envelope";
		$form->Description = get($data, "Description");
		$form->Content = get($data, "Content");
		$form->Template = get($data, "Template") ?? "t";
		$form->BlockTimeout = get($data, "BlockTimeout") ?? 60000;
		$form->BackLabel = get($data, "BackLabel") ?? null;
		$form->BackPath = get($data, "BackPath") ?? null;
		$form->ContentClass = "col-lg-8";
		module("Field");
		$name = get(\_::$User, "Name") ?? receiveGet("Name");
		$email = get(\_::$User, "Email") ?? receiveGet("Email");
		$msg = receiveGet("Message");
		$form->Children = [new MiMFa\Module\Field("text", "Subject", $msg, required: true)];
		if (!\_::$User->Email) {
			$form->Children[] = new MiMFa\Module\Field("text", "Name", $name, required: true, lock: !isEmpty($name));
			$form->Children[] = new MiMFa\Module\Field("email", "Email", $email, required: true, lock: !isEmpty($email));
		}
		$form->Children[] = new MiMFa\Module\Field("texts", "Message", $msg, required: true);
		swap($form, $data);
		$form->Render();
	})->Handle();
?>