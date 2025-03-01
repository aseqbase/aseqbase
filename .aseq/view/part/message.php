<?php
$data = $data ?? [];
module("Form");
(new MiMFa\Library\Router())
	->Post(function () use ($data) {
		$form = new MiMFa\Module\Form();
		if (!\Req::Post("Message"))
			\Res::Render($form->GetError("Your message could not be empty!"));
		else {
			$form->MailSubject = \Req::$Domain . ": Message from '" . (\Req::Post("Name") ?? get(\_::$Back->User, "Name")) . "'";
			$form->ReceiverEmail = \_::$Info->ReceiverEmail;
			$form->SenderEmail = \Req::Post("Email") ?? get(\_::$Back->User, "Email");
			swap($form, $data);
			$form->Handle();
		}
	})
	->Get(function () use ($data) {
		$form = new MiMFa\Module\Form();
		$form->Title = get($data, "Title") ?? "Leave a message to Us!";
		$form->Image = get($data, "Image") ?? "envelope";
		$form->Description = get($data, "Description");
		$form->Content = get($data, "Content");
		$form->Template = get($data, "Template") ?? "t";
		$form->BlockTimeout = get($data, "BlockTimeout") ?? 12;
		$form->BackLabel = get($data, "BackLabel") ?? null;
		$form->BackPath = get($data, "BackPath") ?? null;
		module("Field");
		$name = get(\_::$Back->User, "Name") ?? \Req::Get("Name");
		$email = get(\_::$Back->User, "Email") ?? \Req::Get("Email");
		$msg = \Req::Get("Message");
		$form->Children = [];
		if (!\_::$Back->User->Email) {
			$form->Children[] = new MiMFa\Module\Field("text", "Name", $name, required: true, lock: !isEmpty($name));
			$form->Children[] = new MiMFa\Module\Field("Email", "Email", $email, required: true, lock: !isEmpty($email));
		}
		$form->Children[] = new MiMFa\Module\Field("texts", "Message", $msg, required: true);
		swap($form, $data);
		$form->Render();
	})->Handle();
?>