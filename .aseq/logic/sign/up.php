<?php
module("SignUpForm");
$module = new \MiMFa\Module\SignUpForm();
swap($module, $data);
$module->Render();
return $module->Result;
?>