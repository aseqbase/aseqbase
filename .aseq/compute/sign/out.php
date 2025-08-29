<?php
module("SignInForm");
$module = new \MiMFa\Module\SignInForm();
swap($module, $data);
render($module->Delete());
return $module->Result;
?>