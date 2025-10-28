<?php
module("SignInForm");
$module = new \MiMFa\Module\SignInForm();
pod($module, $data);
response($module->Delete());
return $module->Result;
?>