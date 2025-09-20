<?php

module("SignUpForm");
$module = new \MiMFa\Module\SignUpForm();
swap($module, $data);
$module->Render();
if($module->Result)
    if (\User::$InitialStatus < \User::$ActiveStatus)
        load(\User::$ActiveHandlerPath."?Email=".urlencode(\_::$User->TemporaryEmail));
    else {
        \_::$User->Refresh();
        load(\User::$InHandlerPath);
    }
return $module->Result;
?>