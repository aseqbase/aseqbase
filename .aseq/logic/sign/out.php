<?php
module("SignInForm");
$module = new \MiMFa\Module\SignInForm();
swap($module, $data);
view(\_::$Config->DefaultViewName, ["Content" => $module->Delete()]);
return $module->Result;
?>