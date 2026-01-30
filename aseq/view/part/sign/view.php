<?php
use MiMFa\Library\Struct;
use MiMFa\Library\Convert;
try{
$data = !isEmpty($data) ? $data : \_::$User->Get(preg_find("/\b[^\/\\\\?]+/", \_::$Router->Direction??""));
if (!isEmpty($data)) {
    module("Form");
    module("Field");
    $form = new \MiMFa\Module\Form();
    $form->Title = $data["Name"];
    $form->Image = $data["Image"];
    $form->BackLabel = null;
    $form->BackPath = null;
    $form->SubmitLabel = null;
    $form->ResetLabel = null;
    $form->ResetLabel = null;
    $form->AddChild(Struct::Heading3($data["Signature"]));
    $form->AddChild(Struct::Paragraph(between(Struct::Convert($data["Bio"]), "A New User!")));
    if (isValid($data["Organization"]))
        $form->AddChild(new \MiMFa\Module\Field("label", "Organization", Struct::Convert($data["Organization"]), lock: true));
    if (isValid($data["MetaData"]))
        foreach (Convert::ToSequence(Convert::FromJson($data["MetaData"])) as $key => $value)
            if (preg_match("/^public_/i", $key))
                $form->AddChild(new \MiMFa\Module\Field("label", strToProper(preg_replace("/^public_/i", "", $key)), Struct::Convert($value), lock: true));

    $form->Render();

    echo Struct::Style("
    .{$form->Name} .image {
        border-radius: 100%;
        margin: var(--size-1) 25%;
        width: 50%;
    }
    .{$form->Name} .field .label {
        padding-inline-start: calc(var(--bs-gutter-x) * .5);
    }");
}
}
catch(\Exception $ex) {
    route(404);
}