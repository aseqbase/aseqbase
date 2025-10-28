<?php
use MiMFa\Library\Convert;
$data = $data ?? [];
module("Form");
$form = new MiMFa\Module\Form();
$received = receivePost();
if (!get($received, "Message"))
	response($form->GetError("Your message could not be empty!"));
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
	pod($form, $data);
	$form->Render();
}