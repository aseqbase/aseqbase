<?php
MODULE("SignRecoverForm");
$mod = new \MiMFa\Module\SignRecoverForm();
if(isset($_REQUEST["Signature"]) || isset($_REQUEST["Password"])) $mod->Action();
else {
    try{
        \_::$INFO->User->ReceiveRecoveryEmail();
        $mod->Description = "Recover account for ".\_::$INFO->User->TemporaryEmail;
    }
    catch(\Exception $ex){
        $mod->Description = "Send recovery email for the acoount";
    }
    $mod->Title = "Account Recovery";
    $mod->Image = "undo-alt";
    echo "<div class='page'>";
    $mod->Draw();
    echo "</div>";
}
?>