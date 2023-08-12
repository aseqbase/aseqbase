<?php
use MiMFa\Library\HTML;
use MiMFa\Library\User;
use MiMFa\Library\Local;
if(isset($_REQUEST["Signature"])) {
    unset($_REQUEST["submit"]);
    print_r($_REQUEST);
    if(isValid($_REQUEST,"Image")){
        $person = \_::$INFO->User->Get(\_::$INFO->User->TemporarySignature);
        if(isValid($person,"Image")) Local::DeleteFile(getValid($person,"Image"));
        $_REQUEST["Image"] = Local::UploadImage(getValid($_REQUEST,"Image"),\_::$PUBLIC_DIR."images/");
    } else unset($_REQUEST["Image"]);
    try{
        if(\_::$INFO->User->Set($_REQUEST)){
            echo HTML::Success("Profile updated successfully!");
            if($_REQUEST["Email"] != \_::$INFO->User->TemporaryEmail){
                \_::$INFO->User->SignOut();
                \_::$INFO->User->TemporaryEmail = $_REQUEST["Email"];
                load(User::$ActiveHandlerPath);
            }
            else if($_REQUEST["Signature"] != \_::$INFO->User->TemporarySignature){
                \_::$INFO->User->SignOut();
                load(User::$InHandlerPath);
            }
        }
        else echo HTML::Error("There a problem occured! You can not choice a duplicate signature!");
    }catch(\Exception $ex){echo HTML::Error($ex);}
    die;
}
else {
    TEMPLATE("Main");
    $templ = new \MiMFa\Template\Main();
    $templ->Content = function(){
        $user = \_::$INFO->User->LoadProfile();
        if(isValid($user)){
            echo "<div class='page'>";
            MODULE("Form");
            MODULE("Field");
            $form = new \MiMFa\Module\Form();
            $form->Title = "Edit Profile";
            $form->SubmitLabel = "Update";
            $form->ResetLabel = null;
            $form->AddChild(new \MiMFa\Module\Field("image","Image", $user["Image"], "Click to change the image!"));
            $form->AddChild(new \MiMFa\Module\Field("email","Email", $user["Email"],(User::$InitialStatus < User::$ActiveStatus)?"Your account will needs to activation, if change the field!":"Each email account can have one profile!"));
            $form->AddChild(new \MiMFa\Module\Field("text","Signature", $user["Signature"],"A unique name exclusive for this profile"));
            $form->AddChild(new \MiMFa\Module\Field("text","Name", $user["Name"],"Your full name, you will known by this around the site"));
            $form->AddChild(new \MiMFa\Module\Field("text","First Name", $user["FirstName"]));
            $form->AddChild(new \MiMFa\Module\Field("text","Middle Name", $user["MiddleName"]));
            $form->AddChild(new \MiMFa\Module\Field("text","Last Name", $user["LastName"]));
            $form->AddChild(new \MiMFa\Module\Field("dropdown","Gender", $user["Gender"], null,array("Unspecified", "Male"=>"Male","Female"=>"Female","X"=>"X (Transexual)")));
            $form->AddChild(new \MiMFa\Module\Field("phone","Contact", $user["Contact"],"A phone number with your national code"));
            $form->AddChild(new \MiMFa\Module\Field("text","Address", $user["Address"],"Full postal address"));
            $form->AddChild(new \MiMFa\Module\Field("text","Organization", $user["Organization"],"Your organization, institute, company, etc."));
            $form->AddChild(new \MiMFa\Module\Field("textarea","Description", $user["Description"]));
            $form->Draw();
            echo "</div>";
        }
    };
    $templ->Draw();
}
?>