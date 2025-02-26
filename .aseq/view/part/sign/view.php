<?php
use MiMFa\Library\User;
use MiMFa\Library\HTML;
$user = \_::$Back->User->Get();
if (isValid($user)) {
    module("Form");
    module("Field");
    $form = new \MiMFa\Module\Form();
    $form->Title = $user["Name" ];
    $form->Description = between($user["Bio" ], "A New User!") .
        Html::Rack(join(PHP_EOL, [
            Html::Button("Dashboard", User::$DashboardHandlerPath, ["class"=> "col-lg"]),
            Html::Button("Edit Profile", User::$EditHandlerPath, ["class"=> "col-lg"]),
            Html::Button("Reset Password", User::$RecoverHandlerPath, ["class"=> "col-lg"]),
            Html::Button("Sign Out", User::$OutHandlerPath, ["class"=> "col-lg"])
        ]));
    $form->Image = $user["Image" ];
    $form->BackLabel = "More";
    $form->BackPath = User::$DashboardHandlerPath;
    $form->SubmitLabel = null;
    $form->ResetLabel = null;
    $form->AddChild(new \MiMFa\Module\Field("label", "Email", $user["Email"], lock: true));
    $form->AddChild(new \MiMFa\Module\Field("label", "Signature" , $user["Signature" ], lock: true));
    if (isValid($user["FirstName"]))
        $form->AddChild(new \MiMFa\Module\Field("label", "First Name", $user["FirstName"], lock: true));
    if (isValid($user["MiddleName"]))
        $form->AddChild(new \MiMFa\Module\Field("label", "Middle Name", $user["MiddleName"], lock: true));
    if (isValid($user["LastName"]))
        $form->AddChild(new \MiMFa\Module\Field("label", "Last Name", $user["LastName"], lock: true));
    if (isValid($user["Gender" ]))
        $form->AddChild(new \MiMFa\Module\Field("label", "Gender" , $user["Gender" ], lock: true));
    if (isValid($user["Contact"]))
        $form->AddChild(new \MiMFa\Module\Field("label", "Contact", $user["Contact"], lock: true));
    if (isValid($user["Address" ]))
        $form->AddChild(new \MiMFa\Module\Field("label", "Address" , $user["Address" ], lock: true));
    if (isValid($user["Organization"]))
        $form->AddChild(new \MiMFa\Module\Field("label", "Organization", $user["Organization"], lock: true));

   $form->Render();

   echo Html::Style("
    .{$form->Name} .image {
        border-radius: 100%;
        margin: var(--size-1) 25%;
        width: 50%;
    }");
}
?>