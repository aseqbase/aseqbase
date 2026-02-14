<?php

use MiMFa\Library\Struct;
use MiMFa\Library\Convert;
$user = \_::$User->Get();
if ($user) {
    module("Form");
    module("Field");
    $form = new \MiMFa\Module\Form();
    $form->Title = $user["Name" ];
    $form->Description = between($user["Bio" ], "A New User!") .
        Struct::Rack(join(PHP_EOL, [
            Struct::Button("Dashboard", \_::$User->DashboardHandlerPath, ["class"=> "col-lg"]),
            Struct::Button("Edit Profile", \_::$User->EditHandlerPath, ["class"=> "col-lg"]),
            Struct::Button("Reset Password", \_::$User->RecoverHandlerPath, ["class"=> "col-lg"]),
            Struct::Button("Sign Out", \_::$User->OutHandlerPath, ["class"=> "col-lg"])
        ]));
    $form->Image = $user["Image" ];
    $form->BackLabel = "More";
    $form->BackPath = \_::$User->DashboardHandlerPath;
    $form->SubmitLabel = null;
    $form->ResetLabel = null;
    $form->AddItem(new \MiMFa\Module\Field("label", "Email", $user["Email"], lock: true));
    $form->AddItem(new \MiMFa\Module\Field("label", "Signature" , $user["Signature" ], lock: true));
    if (isValid($user["FirstName"]))
        $form->AddItem(new \MiMFa\Module\Field("label", "First Name", $user["FirstName"], lock: true));
    if (isValid($user["MiddleName"]))
        $form->AddItem(new \MiMFa\Module\Field("label", "Middle Name", $user["MiddleName"], lock: true));
    if (isValid($user["LastName"]))
        $form->AddItem(new \MiMFa\Module\Field("label", "Last Name", $user["LastName"], lock: true));
    if (isValid($user["Gender" ]))
        $form->AddItem(new \MiMFa\Module\Field("label", "Gender" , $user["Gender" ], lock: true));
    if (isValid($user["Contact"]))
        $form->AddItem(new \MiMFa\Module\Field("label", "Contact", $user["Contact"], lock: true));
    if (isValid($user["Address" ]))
        $form->AddItem(new \MiMFa\Module\Field("label", "Address" , $user["Address" ], lock: true));
    if (isValid($user["Organization"]))
        $form->AddItem(new \MiMFa\Module\Field("label", "Organization", $user["Organization"], lock: true));
    if (isValid($user["MetaData"]))
        foreach (Convert::ToSequence(Convert::FromJson($user["MetaData"])) as $key => $value)
            if (preg_match("/^public_/i", $key))
                $form->AddItem(new \MiMFa\Module\Field("label", strToProper(preg_replace("/^public_/i", "", $key)), $value, lock: true));

   $form->Render();

   echo Struct::Style("
    .{$form->MainClass} .image {
        border-radius: 100%;
        margin: var(--size-1) 25%;
        width: 50%;
    }");
}
else part(\_::$User->InHandlerPath);