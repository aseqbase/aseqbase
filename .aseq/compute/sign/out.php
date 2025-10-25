<?php
module("SignInForm");
$module = new \MiMFa\Module\SignInForm();
dip($module, $data);
response($module->Delete());
return $module->Result;
?>