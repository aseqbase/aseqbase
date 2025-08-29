<?php
use \MiMFa\Library\User;
module("SignUpForm");
$module = new \MiMFa\Module\SignUpForm();
swap($module, $data);
$module->Render();
if($module->Result)
    if (User::$InitialStatus < User::$ActiveStatus)
        load(User::$ActiveHandlerPath."?Email=".urlencode(\_::$Back->User->TemporaryEmail));
    else {
        \_::$Back->User->Refresh();
        load(User::$InHandlerPath);
    }
return $module->Result;
?>