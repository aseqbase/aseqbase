<?php
module("SignUpForm");
$module = new \MiMFa\Module\SignUpForm();
$module->Title = "Sign Up";
$module->Image = "user";
swap($module, $data);
$module->Render();
?>