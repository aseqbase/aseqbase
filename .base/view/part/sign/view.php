<?php
use MiMFa\Library\User;
use MiMFa\Library\HTML;
if(ACCESS(\_::$CONFIG->UserAccess)){
    $user = \_::$INFO->User->Get();
    if(isValid($user)){
        echo "<div class='page'>";
        MODULE("Form");
        MODULE("Field");
        $form = new \MiMFa\Module\Form();
        $form->Title = $user["Name"];
        $form->Description = between($user["Bio"],"New User!").
            HTML::Center([
                HTML::Button("Dashboard", User::$DashboardHandlerPath,["class"=>"col-lg"]),
                HTML::Button("Edit Profile", User::$EditHandlerPath,["class"=>"btn-block"]),
                HTML::Button("Reset Password", User::$RecoverHandlerPath,["class"=>"btn-block"]),
                HTML::Button("Sign Out", User::$OutHandlerPath,["class"=>"btn-block"])
            ]);
        $form->Image = $user["Image"];
        $form->BackLabel = "More";
        $form->BackLink = User::$DashboardHandlerPath;
        $form->SubmitLabel = null;
        $form->ResetLabel = null;
        $form->AddChild(new \MiMFa\Module\Field("label","Email", $user["Email"], lock:true));
        $form->AddChild(new \MiMFa\Module\Field("label","Signature", $user["Signature"], lock:true));
        $form->AddChild(new \MiMFa\Module\Field("label","First Name", $user["FirstName"], lock:true));
        $form->AddChild(new \MiMFa\Module\Field("label","Middle Name", $user["MiddleName"], lock:true));
        $form->AddChild(new \MiMFa\Module\Field("label","Last Name", $user["LastName"], lock:true));
        $form->AddChild(new \MiMFa\Module\Field("label","Gender", $user["Gender"], lock:true));
        $form->AddChild(new \MiMFa\Module\Field("label","Contact", $user["Contact"], lock:true));
        $form->AddChild(new \MiMFa\Module\Field("label","Address", $user["Address"], lock:true));
        $form->AddChild(new \MiMFa\Module\Field("label","Organization", $user["Organization"], lock:true));
        $form->Draw();
        echo HTML::Style("
            .{$form->Name} .image {
			    border-radius: 100%;
                margin: var(--Size-1) 25%;
                width: 50%;
            }");
        echo "</div>";
    }
}
?>