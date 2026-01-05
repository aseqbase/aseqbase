<?php
use MiMFa\Library\Struct;

use MiMFa\Library\Local;

popReceived("submit", null, "post");
$imgchange = false;
$received = receivePost();
if ($imgObj = receiveFile("Image")) {
    if (isValid($imgObj, "size") && Local::IsFileObject($imgObj)) {
        $img = \_::$User->GetValue("Image");
        message("Trying to change the profile picture!");
        if (isValid($img) && !Local::DeleteFile($img))
            error("Could not delete your previous picture!");
        else {
            $img = Local::StoreImage($imgObj, \_::$Address->PublicDirectory . "image".DIRECTORY_SEPARATOR."profile".DIRECTORY_SEPARATOR);
            if (!is_null($img)) {
                $received["Image"] = Local::GetUrl($img);
                $imgchange = true;
            }
        }
    } else
        unset($received["Image"]);
} else {
    //if(\_::$Front->Confirm("Are you sure to remove your profile picture?")){
        $img = \_::$User->GetValue("Image");
        message("Trying to remove the profile picture!");
        if (isValid($img) && !Local::DeleteFile($img)) {
            error("Could not delete the profile picture!");
            unset($received["Image" ]);
        } else {
            $received["Image" ] = null;
            $imgchange = true;
        }
    //}
}
try {
    pop($received, "Submit");
    if (\_::$User->Set($received)) {
        if (getValid($received, "Email", \_::$User->TemporaryEmail) != \_::$User->TemporaryEmail) {
            success("The 'email address' modifyed successfully!");
            \_::$User->SignOut();
            \_::$User->TemporaryEmail = $received["Email"];
            load(\_::$User->ActiveHandlerPath);
        } elseif (getValid($received, "Signature" , \_::$User->Signature) != \_::$User->Signature) {
            success("The 'signature' modifyed successfully!");
            \_::$User->SignOut();
            load(\_::$User->InHandlerPath);
        } elseif ($imgchange) {
            success("'Profile picture' modifyed successfully!");
            $img = \_::$User->TemporaryImage;
            \_::$User->Refresh();
            if (isValid($img) != isValid(\_::$User->TemporaryImage))
                reload();
            else
                script("
                    _(\"form .content img\").attr('src','" . \_::$User->TemporaryImage . "');
                    _('input[type=file]').val(null);
                ");
            return true;
        } else
            success("Profile updated successfully!");
    } else
        error(get($received, "Signature" )==\_::$User->Signature?"You did not change anythings!":"Something went wrong!" . Struct::$Break . "You can not choice a duplicate signature!");
} catch (\Exception $ex) {
    error($ex);
}
return false;
?>