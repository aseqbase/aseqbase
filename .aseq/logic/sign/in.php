<?php
module("SignInForm");
$module = new \MiMFa\Module\SignInForm();
swap($module, $data);
$module->Render();
return $module->Result;
?>