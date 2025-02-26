<?php
module("SignInForm");
$mod = new \MiMFa\Module\SignInForm();
view(\_::$Config->DefaultViewName, ["Content" => $mod->Delete()]);
return $mod->Result;
?>