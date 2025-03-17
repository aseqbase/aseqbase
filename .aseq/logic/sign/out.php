<?php
module("SignInForm");
$module = new \MiMFa\Module\SignInForm();
swap($module, $data);
\_::$Front->Prepend("body", $module->Delete());
return $module->Result;
?>