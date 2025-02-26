<?php
module("SignInForm");
$mod = new \MiMFa\Module\SignInForm();
$mod->Render();
return $mod->Result;
?>