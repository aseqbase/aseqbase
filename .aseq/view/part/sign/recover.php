<?php
module("SignRecoverForm");
$mod = new \MiMFa\Module\SignRecoverForm();
try {
    \_::$Back->User->ReceiveRecoveryEmail();
    $mod->Description = "Recover account for " . \_::$Back->User->TemporaryEmail;
} catch (\Exception $ex) {
    $mod->Description = "Send recovery email for the acoount";
}
$mod->Title = "Account Recovery";
$mod->Image = "undo-alt";
return $mod->Render();
?>