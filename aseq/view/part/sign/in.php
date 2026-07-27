<?php
$data = $data??[];
module("SignInForm");
$module = new \MiMFa\Module\SignInForm();
$module->Class = "container";
$module->SignatureValue = get($data, "Signature");
$module->PasswordValue =  get($data, "Password");
$module->ContentClass = "col-lg";
pod($module, $data);
if($module->AllowHeader && $module->ContentClass = "col=lg") $module->ContentClass = "col-lg-5";
$module->Render();