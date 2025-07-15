<?php
use MiMFa\Library\Html;
use MiMFa\Library\User;
use MiMFa\Library\Local;

\Req::Grab("submit", "post");
$imgchange = false;
$received = \Req::ReceivePost();
if ($imgObj = \Req::ReceiveFile("Image")) {
    if (isValid($imgObj, "size") && Local::IsFileObject($imgObj)) {
        $img = \_::$Back->User->GetValue("Image");
        \Res::Message("Trying to change the profile picture!");
        if (isValid($img) && !Local::DeleteFile($img))
            \Res::Error("Could not delete your previous picture!");
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
        \Res::Message("Trying to remove the profile picture!");
        if (isValid($img) && !Local::DeleteFile($img)) {
            \Res::Error("Could not delete the profile picture!");
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
            \Res::Success("The email address modifyed successfully!");
            \_::$Back->User->SignOut();
            \_::$Back->User->TemporaryEmail = $received["Email"];
            \Res::Load(User::$ActiveHandlerPath);
        } elseif (getValid($received, "Signature" , \_::$Back->User->Signature) != \_::$Back->User->Signature) {
            \Res::Success("The signature modifyed successfully!");
            \_::$Back->User->SignOut();
            \Res::Load(User::$InHandlerPath);
        } elseif ($imgchange) {
            \Res::Success("Profile picture modifyed successfully!");
            $img = \_::$Back->User->TemporaryImage;
            \_::$Back->User->Refresh();
            if (isValid($img) != isValid(\_::$Back->User->TemporaryImage))
                \Res::Reload();
            else
                \Res::Script("
$(\"form .content img\").attr('src','" . \_::$Back->User->TemporaryImage . "');
$('input[type=file]').val(null);
");
            return true;
        } else
            \Res::Success("Profile updated successfully!");
    } else
        \Res::Error(\Req::ReceivePOST("Signature" )==\_::$Back->User->Signature?"You did not change anythings!":"There a problem occured!" . Html::$Break . "You can not choice a duplicate signature!");
} catch (\Exception $ex) {
    \Res::Error($ex);
}
return false;
?>