<?php
use MiMFa\Library\HTML;
MODULE("SignInForm");
$mod = new \MiMFa\Module\SignInForm();
if(isset($_REQUEST["Signature"])) $mod->Action();
else {
    $mod->Title = "Sign In";
    $mod->Image = "sign-in";
    $mod->Class = "container";
    $mod->ContentClass = "col-lg-5";
    echo HTML::Page($mod->Capture());
}
?>