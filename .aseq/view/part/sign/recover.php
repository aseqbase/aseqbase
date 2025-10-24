<?php
module("SignRecoverForm");
$module = new \MiMFa\Module\SignRecoverForm();
try {
    \_::$User->ReceiveRecoveryEmail();
    $module->Description = "Recover account for " . \_::$User->TemporaryEmail;
} catch (\Exception $ex) {
    $module->Description = "Send recovery email for the acoount";
}
dip($module, $data);
return $module->Render();