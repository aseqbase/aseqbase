<?php
module("SignUpForm");
$mod = new \MiMFa\Module\SignUpForm();
$mod->Render();
return $mod->Result;
?>