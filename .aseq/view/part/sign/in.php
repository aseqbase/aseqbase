<?php
module("SignInForm");
$module = new \MiMFa\Module\SignInForm();
$module->Class = "container";
$module->SignatureValue = get($data, "Signature");
$module->PasswordValue =  get($data, "Password");
$module->ContentClass = "col-lg-5";
swap($module, $data);
$module->Render();