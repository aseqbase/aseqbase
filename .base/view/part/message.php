<?php
MODULE("Form");
$form = new MiMFa\Module\Form();
if (POST("Message")) {
	$form->MailSubject = \_::$DOMAIN.": Message from '".(POST("Name")??getValid(\_::$INFO->User,"Name"))."'";
	$form->ReceiverEmail = \_::$CONFIG->ReceiverEmail;
	$form->SenderEmail = POST("Email")??getValid(\_::$INFO->User,"Email");
	$form->Handle();
} else {
	$form->Title = "Leave a message to Us!";
	$form->Image = "envelope";
	$form->Template = "t";
	$form->BlockTimeout = 12;
	MODULE("Field");
	$name = getValid(\_::$INFO->User,"Name")??GET("Name");
	$email = getValid(\_::$INFO->User,"Email")??GET("Email");
	$msg = GET("Message");
	$form->Children = [
		new MiMFa\Module\Field("text", "Name", $name, required: true, lock:!isEmpty($name)),
		new MiMFa\Module\Field("email", "Email", $email, required: true, lock:!isEmpty($email)),
		new MiMFa\Module\Field("texts", "Message", GET("Message"), required: true)
	];
	$form->Draw();
}
?>