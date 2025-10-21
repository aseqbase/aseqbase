<?php

module("SignUpForm");
$module = new \MiMFa\Module\SignUpForm();
swap($module, $data);
$module->Render();
if($module->Result)
    if (\_::$User->InitialStatus < \_::$User->ActiveStatus)
        load(\_::$User->ActiveHandlerPath."?Email=".urlencode(\_::$User->TemporaryEmail));
    else {
        \_::$User->Refresh();
        load(\_::$User->InHandlerPath);
    }
return $module->Result;
?>