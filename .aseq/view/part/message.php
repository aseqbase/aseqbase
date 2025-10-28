<?php
$data = $data ?? [];
module("Form");
$form = new MiMFa\Module\Form(action:"/contact/message", method: "POST");
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
pod($form, $data);
$form->Render();