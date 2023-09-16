<?php
use MiMFa\Library\HTML;
use MiMFa\Library\User;
use MiMFa\Library\Local;
if(ACCESS(\_::$CONFIG->UserAccess)){
    if(isset($_REQUEST["Signature"])) {
        unset($_REQUEST["submit"]);
        $imgchange = false;
        if(isValid($_FILES,"Image")){
            if(isValid($_FILES["Image"], "size")){
                echo HTML::Result("Trying to change the profile picture!");
                if(isValid(\_::$INFO->User->TemporaryImage) && !Local::DeleteFile(\_::$INFO->User->TemporaryImage))
                    echo HTML::Error("Could not delete your previous picture!");
                else {
                    $img = Local::UploadImage(getValid($_FILES,"Image"),\_::$PUBLIC_DIR."images/");
                    if(!is_null($img)) {
                        $_REQUEST["Image"] = $img;
                        $imgchange = true;
                    }
                }
            } else unset($_REQUEST["Image"]);
        }
        else{
            echo HTML::Result("Trying to remove the profile picture!");
            if(isValid(\_::$INFO->User->TemporaryImage) && !Local::DeleteFile(\_::$INFO->User->TemporaryImage)){
                echo HTML::Error("Could not delete the profile picture!");
                unset($_REQUEST["Image"]);
            }
            else {
                $_REQUEST["Image"] = null;
                $imgchange = true;
            }
        }
        try{
            if(\_::$INFO->User->Set($_REQUEST)){
                if(getValid($_REQUEST, "Email", \_::$INFO->User->TemporaryEmail) != \_::$INFO->User->TemporaryEmail){
                    echo HTML::Success("The email address modifyed successfully!");
                    \_::$INFO->User->SignOut();
                    \_::$INFO->User->TemporaryEmail = $_REQUEST["Email"];
                    load(User::$ActiveHandlerPath);
                }
                elseif(getValid($_REQUEST, "Signature", \_::$INFO->User->Signature) != \_::$INFO->User->Signature){
                    echo HTML::Success("The signature modifyed successfully!");
                    \_::$INFO->User->SignOut();
                    load(User::$InHandlerPath);
                }
                elseif($imgchange){
                    echo HTML::Success("Profile picture modifyed successfully!");
                    $img = \_::$INFO->User->TemporaryImage;
                    \_::$INFO->User->Refresh();
                    if(isValid($img) != isValid(\_::$INFO->User->TemporaryImage)) load();
                    else echo HTML::Script("
$(\"form .content img\").attr('src','".\_::$INFO->User->TemporaryImage."');
$('input[type=file]').val(null);
");
                }
                else echo HTML::Success("Profile updated successfully!");
            }
            else echo HTML::Error("There a problem occured! You can not choice a duplicate signature!");
        }catch(\Exception $ex){echo HTML::Error($ex);}
        die;
    } else {
        $user = \_::$INFO->User->Get();
        if(isValid($user)){
            echo "<div class='page'>";
            MODULE("Form");
            MODULE("Field");
            $form = new \MiMFa\Module\Form();
            $form->Title = "Edit Profile";
            $form->Image = "edit";
            $form->Id = "EditProfile";
            $form->Method = "POST";
            $form->Template = "both";
            $form->Timeout = 60000;
            $form->SubmitLabel = "Update";
            $form->ResetLabel = "Reset";
            $form->AddChild(function() use($user, $form){
                $img = new \MiMFa\Module\Field("image","Image", $user["Image"], "Click to change the image!");
                $img->Scripts .= "document.getElementById('Image2').onchange = function(){
                    let data = new FormData();
                    data.append('Signature', `".\_::$INFO->User->TemporarySignature."`);
                    data.append('Image', this.files[0]);
                    data.append('submit', 'upload');
                    postData(location.href, data, '.{$form->Name}', (data, selector)=>$(selector).append(data+''));
                };";
                return $img;
            });
            $form->AddChild(new \MiMFa\Module\Field("email","Email", $user["Email"],(User::$InitialStatus < User::$ActiveStatus)?"Your account will needs to activation, if change the field!":"Each email account can have one profile!"));
            $form->AddChild(new \MiMFa\Module\Field("text","Signature", $user["Signature"],"A unique name exclusive for this profile"));
            $form->AddChild(new \MiMFa\Module\Field("text","Name", $user["Name"],"Your full name, you will known by this around the site"));
            $form->AddChild(new \MiMFa\Module\Field("textarea","Bio", $user["Bio"], "Tell a public intorduction about yourself"));
            $form->AddChild(new \MiMFa\Module\Field("text","First Name", $user["FirstName"]));
            $form->AddChild(new \MiMFa\Module\Field("text","Middle Name", $user["MiddleName"]));
            $form->AddChild(new \MiMFa\Module\Field("text","Last Name", $user["LastName"]));
            $form->AddChild(new \MiMFa\Module\Field("dropdown","Gender", $user["Gender"], null,array("Unspecified", "Male"=>"Male","Female"=>"Female","X"=>"X (Transexual)")));
            $form->AddChild(new \MiMFa\Module\Field("phone","Contact", $user["Contact"],"A phone number with your national code"));
            $form->AddChild(new \MiMFa\Module\Field("text","Address", $user["Address"],"Full postal address"));
            $form->AddChild(new \MiMFa\Module\Field("text","Organization", $user["Organization"],"Your organization, institute, company, etc."));
            $form->Draw();
            echo "</div>";
        }
    }
}
?>