<?php
module("SignInForm");
$mod = new \MiMFa\Module\SignInForm();
$mod->Title = "Sign In";
$mod->Image = "sign-in";
$mod->Class = "container";
$mod->ContentClass = "col-lg-5";
$mod->Render();
?>