<?php
module("SignInForm");
$module = new \MiMFa\Module\SignInForm();
pod($module, $data);
$module->Render();
return $module->Result;