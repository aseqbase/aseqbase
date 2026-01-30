<?php
module("SignRecoverForm");
$module = new \MiMFa\Module\SignRecoverForm();
try {
    $module->ReceiveRecoveryEmail();
    $module->Description = "Recover account for '" . \_::$User->TemporaryEmail."'";
} catch (\Exception $ex) {
    $module->Description = "Send recovery email for the acoount";
}
pod($module, $data);
return $module->Render();