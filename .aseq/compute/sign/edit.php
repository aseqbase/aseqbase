<?php
use MiMFa\Library\Html;
use MiMFa\Library\User;
use MiMFa\Library\Local;

snapReceive("submit", null, "post");
$imgchange = false;
$received = receivePost();
if ($imgObj = receiveFile("Image")) {
    if (isValid($imgObj, "size") && Local::IsFileObject($imgObj)) {
        $img = \_::$Back->User->GetValue("Image");
        renderMessage("Trying to change the profile picture!");
        if (isValid($img) && !Local::DeleteFile($img))
            renderError("Could not delete your previous picture!");
        else {
            $img = Local::StoreImage($imgObj, \_::$Aseq->PublicDirectory . "image".DIRECTORY_SEPARATOR."profile".DIRECTORY_SEPARATOR);
            if (!is_null($img)) {
                $received["Image"] = Local::GetUrl($img);
                $imgchange = true;
            }
        }
    } else
        unset($received["Image"]);
} else {
    //if(\_::$Front->Confirm("Are you sure to remove your profile picture?")){
        $img = \_::$Back->User->GetValue("Image");
        renderMessage("Trying to remove the profile picture!");
        if (isValid($img) && !Local::DeleteFile($img)) {
            renderError("Could not delete the profile picture!");
            unset($received["Image" ]);
        } else {
            $received["Image" ] = null;
            $imgchange = true;
        }
    //}
}
try {
    if (\_::$Back->User->Set($received)) {
        if (getValid($received, "Email", \_::$Back->User->TemporaryEmail) != \_::$Back->User->TemporaryEmail) {
            renderSuccess("The email address modifyed successfully!");
            \_::$Back->User->SignOut();
            \_::$Back->User->TemporaryEmail = $received["Email"];
            load(User::$ActiveHandlerPath);
        } elseif (getValid($received, "Signature" , \_::$Back->User->Signature) != \_::$Back->User->Signature) {
            renderSuccess("The signature modifyed successfully!");
            \_::$Back->User->SignOut();
            load(User::$InHandlerPath);
        } elseif ($imgchange) {
            renderSuccess("Profile picture modifyed successfully!");
            $img = \_::$Back->User->TemporaryImage;
            \_::$Back->User->Refresh();
            if (isValid($img) != isValid(\_::$Back->User->TemporaryImage))
                reload();
            else
                renderScript("
                    $(\"form .content img\").attr('src','" . \_::$Back->User->TemporaryImage . "');
                    $('input[type=file]').val(null);
                ");
            return true;
        } else
            renderSuccess("Profile updated successfully!");
    } else
        renderError(get($received, "Signature" )==\_::$Back->User->Signature?"You did not change anythings!":"Something went wrong!" . Html::$Break . "You can not choice a duplicate signature!");
} catch (\Exception $ex) {
    renderError($ex);
}
return false;
?>