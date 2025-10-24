<?php
module("SignInForm");
$module = new \MiMFa\Module\SignInForm();
dip($module, $data);
$module->Render();
return $module->Result;