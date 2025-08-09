<?php
module("SignRecoverForm");
$module = new \MiMFa\Module\SignRecoverForm();
try {
    \_::$Back->User->ReceiveRecoveryEmail();
    $module->Description = "Recover account for " . \_::$Back->User->TemporaryEmail;
} catch (\Exception $ex) {
    $module->Description = "Send recovery email for the acoount";
}
swap($module, $data);
return $module->Render();