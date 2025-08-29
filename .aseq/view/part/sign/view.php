<?php
use MiMFa\Library\Html;
use MiMFa\Library\Convert;
$data = !isEmpty($data) ? $data : \_::$Back->User->Get(\_::$Page);
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
    $form->AddChild(Html::Heading($data["Signature"]));
    $form->AddChild(Html::Paragraph(between(Html::Convert($data["Bio"]), "A New User!")));
    if (isValid($data["Organization"]))
        $form->AddChild(new \MiMFa\Module\Field("label", "Organization", Html::Convert($data["Organization"]), lock: true));
    if (isValid($data["MetaData"]))
        foreach (Convert::ToSequence(Convert::FromJson($data["MetaData"])) as $key => $value)
            if (preg_match("/^public_/i", $key))
                $form->AddChild(new \MiMFa\Module\Field("label", strToProper(preg_replace("/^public_/i", "", $key)), Html::Convert($value), lock: true));

    $form->Render();

    echo Html::Style("
    .{$form->Name} .image {
        border-radius: 100%;
        margin: var(--size-1) 25%;
        width: 50%;
    }
    .{$form->Name} .field .label {
        padding-inline-start: calc(var(--bs-gutter-x) * .5);
    }");
}
?>