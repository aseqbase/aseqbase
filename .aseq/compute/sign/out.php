<?php
module("SignInForm");
$module = new \MiMFa\Module\SignInForm();
dip($module, $data);
render($module->Delete());
return $module->Result;
?>