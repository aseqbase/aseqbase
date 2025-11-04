<?php

use MiMFa\Library\Convert;


$user = \_::$User->Get();
if (isValid($user)) {
    echo "<div class='page'>";
    module("Form");
    module("Field");
    $form = new \MiMFa\Module\Form();
    $form->Title = "Edit Profile";
    $form->Image = "edit";
    $form->Id = "EditProfile";
    $form->Method = "POST";
    $form->Template = "both";
    $form->BlockTimeout = 30000;
    $form->Timeout = 60000;
    $form->SubmitLabel = "Update";
    $form->ResetLabel = "Reset";
    $form->AddChild(function () use ($user, $form) {
        $id = Convert::ToId("Image");
        $img = new \MiMFa\Module\Field("image" , "Image" , $user["Image" ], "Click to change the image!", attributes:["id"=>$id]);
        $img->Scripts .= "document.getElementById('$id').onchange = function(){
                    let data = new FormData();
                    data.append('Signature' , `" . \_::$User->TemporarySignature . "`);
                    data.append('Image' , this.files[0]);
                    data.append('submit', 'upload');
                    sendPost(location.href, data, '.{$form->Name}', (data, err)=>$('.{$form->Name}').append(data+''));
                };";
        return $img;
    });
    $form->AddChild(new \MiMFa\Module\Field("email", "Email", $user["Email"], (\_::$User->InitialStatus < \_::$User->ActiveStatus) ? "Your account will need to be activated if you change the field!" : "Each email account can have one profile!"));
    $form->AddChild(new \MiMFa\Module\Field("text", "Signature" , $user["Signature" ], "A unique name exclusive for this profile"));
    $form->AddChild(new \MiMFa\Module\Field("dropdown", "GroupId" , $user["GroupId"], null, table("UserGroup")->SelectPairs("`Id`", "`Title`", "`Id`=".$user['GroupId']." OR (`Id`>=".\_::$User->MinimumGroupId . " AND `Id`<=".\_::$User->MaximumGroupId.")"), title: "Group"));
    $form->AddChild(new \MiMFa\Module\Field("text", "Name" , $user["Name" ], "Your full name, you will known by this around the site"));
    $form->AddChild(new \MiMFa\Module\Field("textarea", "Bio" , $user["Bio" ], "Tell a public intorduction about yourself"));
    $form->AddChild(new \MiMFa\Module\Field("text", "First Name", $user["FirstName"]));
    $form->AddChild(new \MiMFa\Module\Field("text", "Middle Name", $user["MiddleName"]));
    $form->AddChild(new \MiMFa\Module\Field("text", "Last Name", $user["LastName"]));
    $form->AddChild(new \MiMFa\Module\Field("dropdown", "Gender" , $user["Gender" ], null, array("Unspecified", "Male" => "Male", "Female" => "Female", "X" => "X (Transexual)")));
    $form->AddChild(new \MiMFa\Module\Field("phone", "Contact", $user["Contact"], "A phone number with your national code"));
    $form->AddChild(new \MiMFa\Module\Field("text", "Address" , $user["Address" ], "Full postal address"));
    $form->AddChild(new \MiMFa\Module\Field("text", "Organization", $user["Organization"], "Your organization, institute, company, etc."));
    $form->Render();
    echo "</div>";
}
?>