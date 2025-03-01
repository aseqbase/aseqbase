<?php
module("SignInForm");
$module = new \MiMFa\Module\SignInForm();
$module->Title = "Sign In";
$module->Image = "sign-in";
$module->Class = "container";
$module->ContentClass = "col-lg-5";
swap($module, $data);
$module->Render();
?>