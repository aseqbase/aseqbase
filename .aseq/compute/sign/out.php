<?php
module("SignInForm");
$module = new \MiMFa\Module\SignInForm();
swap($module, $data);
library("Async");
\_::$Front->Prepend("body", $module->Delete());
return $module->Result;
?>