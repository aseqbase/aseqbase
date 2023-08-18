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
        $form->Description = between($user["Bio"],"A New User!").
            HTML::Rack(join(PHP_EOL,[
                HTML::Button("Dashboard", User::$DashboardHandlerPath,["class"=>"col-lg"]),
                HTML::Button("Edit Profile", User::$EditHandlerPath,["class"=>"col-lg"]),
                HTML::Button("Reset Password", User::$RecoverHandlerPath,["class"=>"col-lg"]),
                HTML::Button("Sign Out", User::$OutHandlerPath,["class"=>"col-lg"])
            ]));
        $form->Image = $user["Image"];
        $form->BackLabel = "More";
        $form->BackLink = User::$DashboardHandlerPath;
        $form->SubmitLabel = null;
        $form->ResetLabel = null;
        $form->AddChild(new \MiMFa\Module\Field("label","Email", $user["Email"], lock:true));
        $form->AddChild(new \MiMFa\Module\Field("label","Signature", $user["Signature"], lock:true));
        if(isValid($user["FirstName"])) $form->AddChild(new \MiMFa\Module\Field("label","First Name", $user["FirstName"], lock:true));
        if(isValid($user["MiddleName"])) $form->AddChild(new \MiMFa\Module\Field("label","Middle Name", $user["MiddleName"], lock:true));
        if(isValid($user["LastName"])) $form->AddChild(new \MiMFa\Module\Field("label","Last Name", $user["LastName"], lock:true));
        if(isValid($user["Gender"])) $form->AddChild(new \MiMFa\Module\Field("label","Gender", $user["Gender"], lock:true));
        if(isValid($user["Contact"])) $form->AddChild(new \MiMFa\Module\Field("label","Contact", $user["Contact"], lock:true));
        if(isValid($user["Address"])) $form->AddChild(new \MiMFa\Module\Field("label","Address", $user["Address"], lock:true));
        if(isValid($user["Organization"])) $form->AddChild(new \MiMFa\Module\Field("label","Organization", $user["Organization"], lock:true));
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